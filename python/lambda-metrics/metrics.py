import boto3
import json

from datetime import datetime, timedelta

CW_CLIENT = boto3.client('cloudwatch')

def getMonthlyGroups():
    now = datetime.utcnow()
    stats = CW_CLIENT.get_metric_statistics(
        Namespace='AWS/ES',
        MetricName='SearchableDocuments',
        Dimensions=[{
            'Name': 'ClientId',
            'Value': '629523249783',
        },
        {
            'Name': 'DomainName',
            'Value': 'relliker',
        }],
        StartTime=now - timedelta(days=30*12),
        EndTime=now,
        Statistics=['Maximum'],
        # 30 days
        Period=2592000,
        Unit='Count',
        )

    data = []
    labels = []
    print stats
    for datapoint in sorted(stats['Datapoints'], key=lambda datapoint: datapoint['Timestamp']):
        data.append(datapoint['Maximum'])
        labels.append(datapoint['Timestamp'].strftime("%m/%d/%Y"))

    return success_json({
        "labels": labels,
        "datasets": [{
            "data": data,
            "label": "Count",
        }],
    })


def getDailyGroups():
    now = datetime.utcnow()
    stats = CW_CLIENT.get_metric_statistics(
        Namespace='AWS/ES',
        MetricName='SearchableDocuments',
        Dimensions=[{
            'Name': 'ClientId',
            'Value': '629523249783',
        },
        {
            'Name': 'DomainName',
            'Value': 'relliker',
        }],
        StartTime=now - timedelta(days=7*3),
        EndTime=now,
        Statistics=['Maximum'],
        # 1 day
        Period=86400,
        Unit='Count',
        )

    data = []
    labels = []
    print stats
    for datapoint in sorted(stats['Datapoints'], key=lambda datapoint: datapoint['Timestamp']):
        data.append(datapoint['Maximum'])
        labels.append(datapoint['Timestamp'].strftime("%m/%d/%Y"))

    return success_json({
        "labels": labels,
        "datasets": [{
            "data": data,
            "label": "Count",
        }],
    })


def getScrapeCount():
    now = datetime.utcnow()
    stats = CW_CLIENT.get_metric_statistics(
        Namespace='Relliker/Scraper',
        MetricName='Success',
        StartTime=now - timedelta(hours=12),
        EndTime=now,
        Statistics=['Sum'],
        # 1 hour
        Period=3600,
        )

    data = []
    labels = []
    print stats
    for datapoint in sorted(stats['Datapoints'], key=lambda datapoint: datapoint['Timestamp']):
        data.append(datapoint['Sum'])
        labels.append(datapoint['Timestamp'].strftime("%I:%M%p"))

    return success_json({
        "labels": labels,
        "datasets": [{
            "data": data,
            "label": "Count",
        }],
    })


def getScrapeSuccess():
    now = datetime.utcnow()
    stats = CW_CLIENT.get_metric_statistics(
        Namespace='Relliker/Scraper',
        MetricName='Success',
        StartTime=now - timedelta(hours=12),
        EndTime=now,
        Statistics=['Maximum'],
        # 1 hour
        Period=3600,
        )

    data = []
    labels = []
    print stats
    for datapoint in sorted(stats['Datapoints'], key=lambda datapoint: datapoint['Timestamp']):
        data.append(datapoint['Maximum'])
        labels.append(datapoint['Timestamp'].strftime("%I:%M%p"))

    return success_json({
        "labels": labels,
        "datasets": [{
            "data": data,
            "label": "Count",
        }],
    })


def success_json(results):
    return {
        "statusCode": 200,
        "headers": {
            "Access-Control-Allow-Origin": "*"
        },
        "body": json.dumps({
            "errorMessage": None,
            "result": results,
        }),
    }


def error_json(message):
    return {
        "statusCode": 400,
        "headers": {},
        "body": json.dumps({
            "errorMessage": message,
            "result": {
                "labels": [],
                "datasets": [],
            },
        }),
    }


def lambda_handler(event, context):
    query = event['queryStringParameters']
    if not query or 'type' not in query:
        return error_json("No type given.  Did you forget to pass the 'type' query param?")

    statType = query['type']

    if statType == "monthlyGroups":
        return getMonthlyGroups()
    if statType == "dailyGroups":
        return getDailyGroups()
    if statType == "groupRunCounts":
        return getScrapeCount()
    if statType == "groupRunSuccesses":
        return getScrapeSuccess()

    return error_json("Type no found.")
