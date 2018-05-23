# -*- coding: utf-8 -*

import argparse
import cookielib
import os
# import psycopg2
import time
import urllib
import urllib2
import boto3
import re
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
        urllib2.HTTPRedirectHandler(),
        urllib2.HTTPHandler(debuglevel=0),
        urllib2.HTTPSHandler(debuglevel=0),
        urllib2.HTTPCookieProcessor(cookie_jar),
    )
    opener.addheaders = [
        ('User-agent', ('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36'))
    ]
    return opener


def grabTokens(cookie_jar):
    opener = build_opener(cookie_jar)
    response = opener.open('https://www.fitbit.com/login')
    response_body = '\n'.join(response.readlines())
    # <input name="csrfToken" type="hidden" value="XXXXXXXXXXXXXXX" />
    csrfTokenSearch = re.search('<input\s+name=\"csrfToken\".*value=\"([\w]+)\"\s*/>', response_body)
    # <input type="hidden" name="_sourcePage" value="XXXXXXXXXXXXXXX" />
    sourcePageSearch = re.search('<input\s+type=\"hidden\"\s+name=\"_sourcePage\".*value=\"(.+)\"\s*/>', response_body)
    # <input type="hidden" name="__fp" value="XXXXXXXXXXXXXXX" />
    fpSearch = re.search('<input\s+type=\"hidden\"\s+name=\"__fp\".*value=\"(.+)\"\s*/>', response_body)
    return {
           'csrfToken': csrfTokenSearch.group(1),
           '_sourcePage': sourcePageSearch.group(1),
           '__fp': fpSearch.group(1),
       }


def login(username, password, cookie_jar):
    tokens = grabTokens(cookie_jar)
    # print 'csrfToken: {}'.format(tokens['csrfToken'])
    # print '_sourcePage: {}'.format(tokens['_sourcePage'])
    # print '__fp: {}'.format(tokens['__fp'])
    login_data = urllib.urlencode({
        'login': 'Log In',
        'includeWorkflow': '',
        'redirect': '',
        'switchToNonSecureOnRedirect': '',
        'disableThirdPartyLogin': 'false',
        'email': username,
        'password': password,
        'rememberMe': 'true',
        'csrfToken': tokens['csrfToken'],
        '_sourcePage': tokens['_sourcePage'],
        '__fp': tokens['__fp'],
    })
    # This is fitbits new "consent" cookie
    cookie_jar.set_cookie(cookielib.Cookie(
            version=0,
            name='fitbit_gdpr_ok',
            value='true',
            port=None,
            port_specified=False,
            domain='.fitbit.com',
            domain_specified=True,
            domain_initial_dot=True,
            path="/",
            path_specified=True,
            secure=False,
            expires=None,
            discard=False,
            comment=None,
            comment_url=None,
            rest=None
        ))
    opener = build_opener(cookie_jar)
    response = opener.open('https://www.fitbit.com/login', login_data)
    response_body = '\n'.join(response.readlines())
    if '<title>Fitbit Dashboard</title>' not in response_body:
        print response_body
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
    response_body = '\n'.join(response.readlines())
    return response_body


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('-e', '--email', help='Email to use when logging in')
    parser.add_argument('-p', '--password', help='Password to use when logging in')
    # parser.add_argument('--db_host')
    # parser.add_argument('--db_name')
    # parser.add_argument('--db_user')
    # parser.add_argument('--db_password')
    parser.add_argument('--es_host')
    parser.add_argument('--es_port')
    parser.add_argument('--local', action='store_true')
    ARGS = parser.parse_args()
    ARGS.email = ensure_variable(ARGS.email, 'FITBIT_EMAIL')
    ARGS.password = ensure_variable(ARGS.password, 'FITBIT_PASSWORD')
    # ARGS.db_host = ensure_variable(ARGS.db_host, 'DB_HOST')
    # ARGS.db_name = ensure_variable(ARGS.db_name, 'DB_NAME')
    # ARGS.db_user = ensure_variable(ARGS.db_user, 'DB_USER')
    # ARGS.db_password = ensure_variable(ARGS.db_password, 'DB_PASSWORD')
    if ARGS.local:
        ARGS.es_host = 'localhost'
        ARGS.es_port = 9200
    else:
        ARGS.es_host = ensure_variable(ARGS.es_host, 'ES_HOST')
        ARGS.es_port = ensure_variable(ARGS.es_port, 'ES_PORT')

    START_TIME = time.time()
    GLOBAL_COOKIE_JAR = cookielib.LWPCookieJar("cookies.txt")
    # print('[POSTGRESQL] Connecting to {} [User: {}, Name: {}]...'.format(ARGS.db_host, ARGS.db_user, ARGS.db_name))
    # POSTGRES = psycopg2.connect(
    #     host=ARGS.db_host,
    #     user=ARGS.db_user,
    #     database=ARGS.db_name,
    #     password=ARGS.db_password)
    # POSTGRES_CURSOR = POSTGRES.cursor()
    # print('[POSTGRESQL] Connected!')

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
    # POSTGRES_CURSOR.execute(u'''SELECT value FROM settings WHERE key = %s;''', ('starting_letter',))
    # starting_letter = POSTGRES_CURSOR.fetchone()[0]
    starting_letter = 'A'
    starting_letter_index = letters.index(starting_letter)
    # # Start on the next index/letter
    # starting_letter_index = (starting_letter_index + 1) % len(letters)
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
                # try:
                #     POSTGRES_CURSOR.execute(u'''UPDATE groups SET
                #                                   (name, description, last_updated) = (%s, %s, now())
                #                                   WHERE id = %s;''',
                #                             (group.groupName, group.groupDescription, group.groupId))
                #     if POSTGRES_CURSOR.rowcount != 1:
                #         POSTGRES_CURSOR.execute(u'''INSERT INTO groups(id, name, description, last_updated)
                #                                       VALUES (%s, %s, %s, now());''',
                #                                 (group.groupId, group.groupName, group.groupDescription))
                #     POSTGRES.commit()
                # except psycopg2.IntegrityError:
                #     POSTGRES.rollback()
                #
                # POSTGRES_CURSOR.execute(u'''INSERT INTO group_info(id, group_id, created, members)
                #                               VALUES (nextval('group_info_sequence'), %s, now(), %s);''',
                #                         (group.groupId, group.groupMembers))
                # POSTGRES.commit()
                body = {
                    'id': group.groupId,
                    'name': group.groupName,
                    'description': group.groupDescription,
                    'members': group.groupMembers,
                    'timestamp': u'{}'.format(datetime.utcnow().isoformat()),
                }
                ELASTIC_SEARCH.index(index='group-index', doc_type='group_info', id=group.groupId, body=body)

            startIndex += len(groups)
            print('[{}] [....] Analyzed {} groups ({} total)'.format(letter, len(groups), startIndex))
            sys.stdout.flush()
            if len(groups) < NUM_PER_PAGE:
                # POSTGRES_CURSOR.execute(u'''UPDATE settings SET value = %s WHERE key = %s;''',
                #                         (letter, 'starting_letter'))
                # POSTGRES.commit()
                break

        print('[{}] [DONE] Analyzed {} groups in {} seconds'.format(letter, startIndex, time.time() - letter_start_time))
        sys.stdout.flush()

    END_TIME = time.time()
    print(END_TIME - START_TIME)

if __name__ == '__main__':
    main()
