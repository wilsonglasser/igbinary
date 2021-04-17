#!/usr/bin/env bash
if [ $# != 1 ]; then
    echo "Usage: $0 PHP_VERSION" 1>&2
    echo "e.g. $0 8.0" 1>&2
    echo "The PHP_VERSION is the version of the php docker image to use" 1>&2
    exit 1
fi
# -x Exit immediately if any command fails
# -e Echo all commands being executed.
# -u fail for undefined variables
set -xeu
PHP_VERSION=$1
DOCKER_IMAGE=igbinary-$PHP_VERSION-test-runner
docker build --build-arg="PHP_VERSION=$PHP_VERSION" --tag="$DOCKER_IMAGE" -f ci/Dockerfile .
docker run --rm $DOCKER_IMAGE ci/test_inner.sh
# NOTE: php 7.3+ will fail in valgrind because php-src uses custom assembly for its implementation of zend_string_equals
# In order to fix those false positives, a different set of images would be needed where (1) valgrind was installed before compiling php, and (2) php was compiled with support for valgrind (--with-valgrind) to avoid false positives
# docker run --rm $DOCKER_IMAGE ci/test_inner_valgrind.sh
