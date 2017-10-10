#!/bin/bash -x
# Needs to be run before this script executes:
#   sudo yum install -y build-dep python-psycopg2 postgresql-devel gcc

mkdir scraper
virtualenv scraper
pushd scraper
source bin/activate
aws s3 cp s3://relliker.com/scraper/relliker-settings.source relliker-settings.source
source relliker-settings.source
aws s3 cp s3://relliker.com/scraper/group_scraper-1.0.0.tar.gz group_scraper-1.0.0.tar.gz
pip install group_scraper-1.0.0.tar.gz
python -m group_scraper
if [ $? == 0 ]; then
  aws cloudwatch put-metric-data --region=us-west-2 \
      --metric-name "Success" \
      --namespace "Relliker/Scraper"\
      --value 1 \
      --timestamp $(date --utc +%FT%T.%3NZ)
else
  aws cloudwatch put-metric-data --region=us-west-2 \
      --metric-name "Success" \
      --namespace "Relliker/Scraper"\
      --value 0 \
      --timestamp $(date --utc +%FT%T.%3NZ)
fi
popd

sudo shutdown -h now
