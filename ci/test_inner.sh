#!/usr/bin/env bash
# -x Exit immediately if any command fails
# -e Echo all commands being executed.
# -u fail for undefined variables
set -xeu
echo "Run tests in docker"
REPORT_EXIT_STATUS=1 php ci/run-tests-parallel.php -j$(nproc) -P -q --show-diff
echo "Test that package.xml is valid"
pecl package
