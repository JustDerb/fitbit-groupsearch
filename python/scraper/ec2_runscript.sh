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

function reportExitCode {
  aws cloudwatch put-metric-data --region=us-west-2 \
      --metric-name "Success" \
      --namespace "Relliker/Scraper"\
      --value $1 \
      --timestamp $(date --utc +%FT%T.%3NZ)
}

function reportRuntime {
  aws cloudwatch put-metric-data --region=us-west-2 \
      --metric-name "Time" \
      --namespace "Relliker/Scraper"\
      --value $1 \
      --unit "Seconds" \
      --timestamp $(date --utc +%FT%T.%3NZ)
}

EXIT_CODE=0
while [ "$EXIT_CODE" == 0 ]; do
  START=$(date +%s)
  python -m group_scraper
  EXIT_CODE=$?
  echo "Exit code: $EXIT_CODE"
  END=$(date +%s)
  RUNTIME=$((END-START))
  if [ "$EXIT_CODE" == 0 ]; then
    reportExitCode 1
  else
    reportExitCode 0
  fi
  reportRuntime $RUNTIME
done

popd
if [ ! -e ".no_shutdown" ]; then
  sudo shutdown -h now
fi
