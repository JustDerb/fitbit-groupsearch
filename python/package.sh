#!/bin/bash -e

function package {
  if [[ ! -d "$1/local" ]]; then
    echo "Please ensure you created a virtualenv inside"
    echo "$1, and then ran:"
    echo "  pip install -r requirements.txt"
    echo "before packaging this lambda function"
    exit 1
  fi
  pushd "$1"
  echo "[PACKAGE] Packaging $1..."
  lambda-uploader --no-upload
  mv lambda_function.zip "../$1.zip"
  popd
  echo "[PACKAGE] $1 created"
}

for lambda in $(ls | grep "lambda-"); do
  if [[ -d $lambda ]]; then
    package "$lambda"
  fi
done;
