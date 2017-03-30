from __future__ import print_function
import argparse
import os
import sys
import json
import boto3
from elasticsearch import Elasticsearch, RequestsHttpConnection
from requests_aws4auth import AWS4Auth


# https://www.elastic.co/guide/en/elasticsearch/reference/2.3/query-dsl-query-string-query.html#_reserved_characters
def needs_escaping(character):
    escape_chars = {
        '+': True,
        '-': True,
        '=': True,
        '&': True,
        '&': True,
        '|': True,
        '|': True,
        '>': True,
        '<': True,
        '!': True,
        '(': True,
        ')': True,
        '{': True,
        '}': True,
        '[': True,
        ']': True,
        '^': True,
        '"': True,
        '~': True,
        '*': True,
        '?': True,
        ':': True,
        '\\': True,
        '/': True,
    }
    return escape_chars.get(character, False)


def sanitize_search(term):
    sanitized = ''
    for character in term:
        if needs_escaping(character):
            sanitized += '\\{}'.format(character)
        else:
            sanitized += character
    return sanitized


def ensure_variable(actual, env_name):
    if actual:
        return actual
    else:
        try:
            return os.environ[env_name]
        except KeyError:
            raise BaseException('No {} env variable found. Run --help for more info'.format(env_name))


def lambda_handler(event, context):
    query = event['queryStringParameters']
    if not query or 's' not in query:
        return {
            "statusCode": 400,
            "headers": {},
            "body": json.dumps({
                "errorMessage": "No search given.  Did you forget to pass the 's' query param?",
                "result": {}
            })
        }

    if 'o' not in query:
        offset = 0
    else:
        try:
            offset = int(query['o'])
        except ValueError:
            return {
                "statusCode": 400,
                "headers": {},
                "body": json.dumps({
                    "errorMessage": "Invalid 'o' query param - must be an integer",
                    "result": {}
                })
            }

    search_term = query['s']

    parser = argparse.ArgumentParser()
    # parser.add_argument('term', default='')
    parser.add_argument('--es_host')
    parser.add_argument('--es_port')
    parser.add_argument('--local', action='store_true')
    args = parser.parse_args()
    if args.local:
        args.es_host = 'localhost'
        args.es_port = 9200
        # search_term = args.term
    else:
        args.es_host = ensure_variable(args.es_host, 'ES_HOST')
        args.es_port = ensure_variable(args.es_port, 'ES_PORT')

    print('[ELASTICSEARCH] Connecting to {}...'.format(args.es_host))
    if not args.local:
        session = boto3.Session()
        credentials = session.get_credentials()
        region = os.environ['AWS_REGION']
        aws_auth = AWS4Auth(credentials.access_key, credentials.secret_key, region, 'es', session_token=credentials.token)

        elastic_search_api = Elasticsearch(
            hosts=[{"host": args.es_host, "port": int(args.es_port)}],
            http_auth=aws_auth,
            use_ssl=True,
            verify_certs=True,
            connection_class=RequestsHttpConnection
        )
    else:
        elastic_search_api = Elasticsearch(
            hosts=[{"host": args.es_host, "port": int(args.es_port)}]
        )
    print('[ELASTICSEARCH] Connected!')

    search_term = sanitize_search(search_term)
    print('[SEARCH] {}'.format(search_term))
    results = elastic_search_api.search(index='group-index', doc_type='group_info', q=search_term,
                                        size=50, from_=offset)

    next_offset = min(len(results['hits']['hits']) + offset + 1, results['hits']['total'])
    print('[SEARCH] Records found: {}'.format(len(results['hits']['hits'])))
    response = {
        "statusCode": 200,
        "headers": {
            "Access-Control-Allow-Origin": "https://relliker.com"
        },
        "body": json.dumps({
            "errorMessage": None,
            "result": results,
            "next_offset": next_offset,
        })
    }

    return response

if __name__ == '__main__':
    if len(sys.argv) > 1:
        fake_query = {
            's': sys.argv[1]
        }
    else:
        fake_query = {}
    lambda_response = lambda_handler({
        'queryStringParameters': fake_query
    }, {})

    print(json.dumps(json.loads(lambda_response['body']), indent=2))
