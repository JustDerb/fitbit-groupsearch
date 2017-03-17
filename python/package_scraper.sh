#!/bin/bash -e

function package {
  pushd "$1"
  echo "[PACKAGE] Packaging $1..."
  python setup.py sdist
  popd
  echo "[PACKAGE] $1 created"
  echo "  Located in $1/dist/"
}

package "scraper"
