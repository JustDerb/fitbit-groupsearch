#!/bin/bash -e

pushd lambda-search
lambda-uploader --no-upload
mv lambda_function.zip ../search.zip
popd
echo "search.zip created"
