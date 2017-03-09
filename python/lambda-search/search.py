from __future__ import print_function
import argparse
import os
import sys
import json
import boto3
from elasticsearch import Elasticsearch, RequestsHttpConnection
from requests_aws4auth import AWS4Auth


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

    search_term = query['s']

    parser = argparse.ArgumentParser()
    parser.add_argument('term')
    parser.add_argument('--es_host')
    parser.add_argument('--local', action='store_false')
    args = parser.parse_args()
    if args.local:
        args.es_host = 'localhost'
        search_term = args.term
    else:
        args.es_host = ensure_variable(args.es_host, 'ES_HOST')

    print('[ELASTICSEARCH] Connecting to {}...'.format(args.es_host))
    if not args.local:
        session = boto3.Session()
        credentials = session.get_credentials()
        region = os.environ['AWS_REGION']
        aws_auth = AWS4Auth(credentials.access_key, credentials.secret_key, region, 'es', session_token=credentials.token)

        elastic_search_api = Elasticsearch(
            hosts=[{"host": args.es_host, "port": 9200}],
            http_auth=aws_auth,
            use_ssl=True,
            verify_certs=True,
            connection_class=RequestsHttpConnection
        )
    else:
        elastic_search_api = Elasticsearch(
            hosts=[{"host": args.es_host, "port": 9200}]
        )
    print('[ELASTICSEARCH] Connected!')

    print('[SEARCH] {}'.format(search_term))
    results = elastic_search_api.search(index='group-index', doc_type='group_info', q=search_term)

    response = {
        "statusCode": 200,
        "headers": {},
        "body": json.dumps({
            "errorMessage": None,
            "result": results
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
