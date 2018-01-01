#!/bin/bash -e

function ensurePythonCommand {
  if ! hash "$1" 2>/dev/null; then
    echo "$1 not found on PATH. Run"
    echo "  pip install $1"
    echo "to install."
    exit 1
  fi
}

function package {
  if [[ ! -e "$1/bin/activate" ]]; then
    echo "[VIRTUALENV] No virtualenv created. Creating now..."
    virtualenv "$1"
  fi
  pushd "$1"
  echo "[VIRTUALENV] Activating virtualenv"
  source ./bin/activate
  echo "[PACKAGE] Installing required dependencies..."
  pip install -r requirements.txt
  echo "[PACKAGE] Packaging $1..."
  lambda-uploader --no-upload
  echo "[VIRTUALENV] Deactivating virtualenv"
  deactivate
  popd
  mv "$1/lambda_function.zip" "$1.zip"
  echo "[PACKAGE] $1 created at $1.zip"
}

ensurePythonCommand virtualenv
ensurePythonCommand lambda-uploader

for lambda in $(ls | grep "lambda-"); do
  if [[ -d $lambda ]]; then
    package "$lambda"
  fi
done;
