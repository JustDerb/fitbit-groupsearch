# Overview

Various backend python scripts that make the site work.

# Lambda APIs

## `lambda-group`

Work in progress lambda to return metadata about FitBit groups that have been
scraped.

### Packaging

Run `package_lambda.sh` to auto-package all lambda functions.

## `lambda-search`

This is the main lambda that is used for all search requests. Simply just hits
the ElasticSearch cluster and returns it's results.

### Packaging

Run `package_lambda.sh` to auto-package all lambda functions.

# Scraper

## `scraper`

This scraper will hit FitBit's main HTML pages to scrape group information from
it. Still, they do not have a public Group API to make this easier...

### Packaging

Run `package_scraper.sh`
