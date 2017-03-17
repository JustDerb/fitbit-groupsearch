import argparse
import cookielib
import os
import psycopg2
import time
import urllib
import urllib2
import boto3
from datetime import datetime

from elasticsearch import Elasticsearch, RequestsHttpConnection
from requests_aws4auth import AWS4Auth

from . import GroupResponseParser

# Hack: http://stackoverflow.com/a/17628350
import sys
if sys.getdefaultencoding() == 'ascii':
    reload(sys)
    sys.setdefaultencoding('UTF8')


def ensure_variable(actual, env_name):
    if actual:
        return actual
    else:
        try:
            return os.environ[env_name]
        except KeyError:
            raise BaseException('No {} env variable found. Run --help for more info'.format(env_name))


def build_opener(cookie_jar):
    opener = urllib2.build_opener(
        # urllib2.HTTPRedirectHandler(),
        urllib2.HTTPHandler(debuglevel=0),
        urllib2.HTTPSHandler(debuglevel=0),
        urllib2.HTTPCookieProcessor(cookie_jar)
    )
    opener.addheaders = [
        ('User-agent', ('Mozilla/4.0 (compatible; MSIE 6.0; '
                        'Windows NT 5.2; .NET CLR 1.1.4322)'))
    ]
    return opener


def login(username, password, cookie_jar):
    opener = build_opener(cookie_jar)
    login_data = urllib.urlencode({
        'login': 'Log In',
        'includeWorkflow': '',
        'redirect': '',
        'switchToNonSecureOnRedirect': '',
        'disableThirdPartyLogin': 'false',
        'email': username,
        'password': password,
        'rememberMe': 'true',
    })
    response = opener.open('https://www.fitbit.com/login', login_data)
    response_body = '\n'.join(response.readlines())
    if 'meta http-equiv="refresh"' not in response_body:
        raise BaseException('Got bad response.  Wrong user/pass?')


def get_group(cookie_jar, prefix, start, total):
    opener = build_opener(cookie_jar)
    query_params = urllib.urlencode({
        'loadMoreWithPrefix': 'on',
        'prefix': prefix,
        'startIndex': start,
        'numResults': total,
        'useWildcard': 'false',
    })
    response = opener.open('https://www.fitbit.com/groups?' + query_params)
    return '\n'.join(response.readlines())


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('-e', '--email', help='Email to use when logging in')
    parser.add_argument('-p', '--password', help='Password to use when logging in')
    parser.add_argument('--db_host')
    parser.add_argument('--db_name')
    parser.add_argument('--db_user')
    parser.add_argument('--db_password')
    parser.add_argument('--es_host')
    parser.add_argument('--es_port')
    parser.add_argument('--local', action='store_true')
    ARGS = parser.parse_args()
    ARGS.email = ensure_variable(ARGS.email, 'FITBIT_EMAIL')
    ARGS.password = ensure_variable(ARGS.password, 'FITBIT_PASSWORD')
    ARGS.db_host = ensure_variable(ARGS.db_host, 'DB_HOST')
    ARGS.db_name = ensure_variable(ARGS.db_name, 'DB_NAME')
    ARGS.db_user = ensure_variable(ARGS.db_user, 'DB_USER')
    ARGS.db_password = ensure_variable(ARGS.db_password, 'DB_PASSWORD')
    if ARGS.local:
        ARGS.es_host = 'localhost'
        ARGS.es_port = 9200
    else:
        ARGS.es_host = ensure_variable(ARGS.es_host, 'ES_HOST')
        ARGS.es_port = ensure_variable(ARGS.es_port, 'ES_PORT')

    START_TIME = time.time()
    GLOBAL_COOKIE_JAR = cookielib.CookieJar()
    print('[POSTGRESQL] Connecting to {} [User: {}, Name: {}]...'.format(ARGS.db_host, ARGS.db_user, ARGS.db_name))
    POSTGRES = psycopg2.connect(
        host=ARGS.db_host,
        user=ARGS.db_user,
        database=ARGS.db_name,
        password=ARGS.db_password)
    POSTGRES_CURSOR = POSTGRES.cursor()
    print('[POSTGRESQL] Connected!')

    print('[ELASTICSEARCH] Connecting to {}...'.format(ARGS.es_host))
    if ARGS.local:
        ELASTIC_SEARCH = Elasticsearch(hosts=[{"host": ARGS.es_host, "port": int(ARGS.es_port)}])
    else:
        session = boto3.Session()
        credentials = session.get_credentials()
        region = os.environ['AWS_REGION']
        aws_auth = AWS4Auth(credentials.access_key, credentials.secret_key, region, 'es',
                            session_token=credentials.token)
        ELASTIC_SEARCH = Elasticsearch(
            hosts=[{"host": ARGS.es_host, "port": int(ARGS.es_port)}],
            http_auth=aws_auth,
            use_ssl=True,
            verify_certs=True,
            connection_class=RequestsHttpConnection
        )
    print('[ELASTICSEARCH] Connected!')

    login(ARGS.email, ARGS.password, GLOBAL_COOKIE_JAR)
    # http://www.asciitable.com/
    letters = []
    for i in range(ord('!'), ord('`') + 1):
        letters.append(chr(i))
    # Skip a-z (lowercase)
    for i in range(ord('{'), ord('~') + 1):
        letters.append(chr(i))

    # Rotate our list so we start with the letter that is in the db
    POSTGRES_CURSOR.execute(u'''SELECT value FROM settings WHERE key = %s;''', ('starting_letter',))
    starting_letter = POSTGRES_CURSOR.fetchone()[0]
    starting_letter_index = letters.index(starting_letter)
    # Start on the next index/letter
    starting_letter_index = (starting_letter_index + 1) % len(letters)
    letters = letters[starting_letter_index:] + letters[:starting_letter_index]

    print('Letter prefixes to fetch:')
    print(letters)

    NUM_PER_PAGE = 100
    for letter in letters:
        letter_start_time = time.time()
        page = 1
        startIndex = 0
        print( '[{}] [....] Starting analysis'.format(letter))
        while True:
            response = get_group(GLOBAL_COOKIE_JAR, letter, startIndex, NUM_PER_PAGE)
            # print response
            parser = GroupResponseParser.GroupResponseParser()
            parser.feed(response)
            groups = parser.groups

            for group in groups:
                try:
                    POSTGRES_CURSOR.execute(u'''UPDATE groups SET
                                                  (name, description, last_updated) = (%s, %s, now())
                                                  WHERE id = %s;''',
                                            (group.groupName, group.groupDescription, group.groupId))
                    if POSTGRES_CURSOR.rowcount != 1:
                        POSTGRES_CURSOR.execute(u'''INSERT INTO groups(id, name, description, last_updated)
                                                      VALUES (%s, %s, %s, now());''',
                                                (group.groupId, group.groupName, group.groupDescription))
                    POSTGRES.commit()
                except psycopg2.IntegrityError:
                    POSTGRES.rollback()

                POSTGRES_CURSOR.execute(u'''INSERT INTO group_info(id, group_id, created, members)
                                              VALUES (nextval('group_info_sequence'), %s, now(), %s);''',
                                        (group.groupId, group.groupMembers))
                POSTGRES.commit()
                body = {
                    'id': unicode(group.groupId, 'UTF-8'),
                    'name': unicode(group.groupName, 'utf-8'),
                    'description': unicode(group.groupDescription, 'utf-8'),
                    'members': group.groupMembers,
                    'timestamp': unicode(datetime.utcnow().isoformat(), 'utf-8'),
                }
                ELASTIC_SEARCH.index(index='group-index', doc_type='group_info', id=group.groupId, body=body)

            startIndex += len(groups)
            print('[{}] [....] Analyzed {} groups ({} total)'.format(letter, len(groups), startIndex))
            if len(groups) < NUM_PER_PAGE:
                POSTGRES_CURSOR.execute(u'''UPDATE settings SET value = %s WHERE key = %s;''',
                                        (letter, 'starting_letter'))
                POSTGRES.commit()
                break

        print('[{}] [DONE] Analyzed {} groups in {} seconds'.format(letter, startIndex, time.time() - letter_start_time))

    END_TIME = time.time()
    print(END_TIME - START_TIME)

if __name__ == '__main__':
    main()