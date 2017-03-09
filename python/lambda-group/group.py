from __future__ import print_function
import argparse
import os
import sys
import json
import psycopg2


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
    if not query or 'id' not in query:
        return {
            "statusCode": 400,
            "headers": {},
            "body": json.dumps({
                "errorMessage": "No group id given.  Did you forget to pass the 'id' query param?",
                "result": {}
            })
        }

    group_id = query['id']

    parser = argparse.ArgumentParser()
    # parser.add_argument('id', default='')
    parser.add_argument('--db_host')
    parser.add_argument('--db_name')
    parser.add_argument('--db_user')
    parser.add_argument('--db_password')
    parser.add_argument('--local', action='store_true')
    args = parser.parse_args()
    args.db_name = ensure_variable(args.db_name, 'DB_NAME')
    args.db_user = ensure_variable(args.db_user, 'DB_USER')
    args.db_password = ensure_variable(args.db_password, 'DB_PASSWORD')
    if args.local:
        args.db_host = 'localhost'
        # group_id = args.id
    else:
        args.db_host = ensure_variable(args.db_host, 'DB_HOST')

    print('[POSTGRES] Connecting to {}...'.format(args.es_host))
    POSTGRES = psycopg2.connect(
        host=args.db_host,
        user=args.db_user,
        database=args.db_name,
        password=args.db_password)
    POSTGRES_CURSOR = POSTGRES.cursor()
    print('[POSTGRES] Connected!')

    print('[ID] {}'.format(group_id))
    # DO SELECT STATEMENT
    # POSTGRES_CURSOR.execute(u'''FILL ME IN''')

    response = {
        "statusCode": 200,
        "headers": {},
        "body": json.dumps({
            "errorMessage": None,
            "result": {}
        })
    }

    return response

if __name__ == '__main__':
    if len(sys.argv) > 1:
        fake_query = {
            'id': sys.argv[1]
        }
    else:
        fake_query = {}
    lambda_response = lambda_handler({
        'queryStringParameters': fake_query
    }, {})

    print(json.dumps(json.loads(lambda_response['body']), indent=2))
