#!/bin/sh
#
# validates against the invoice JSON schema the sample XML invoice files from
# fatturapa.gov.it converted to JSON
#
# Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
# License: BSD 3-Clause

set -e

for name in "samples/IT01234567890_FPA01" "samples/IT01234567890_FPA02" "samples/IT01234567890_FPA03" "samples/IT01234567890_FPR01" "samples/IT01234567890_FPR02" "samples/IT01234567890_FPR03"
do
  echo "validating $name-js.json with javascript"
  ./bin/validate_json.js "$name-js.json"
  echo "validating $name-js.json with PHP"
  ./bin/validate_json.php "$name-js.json"
  echo "validating $name-php.json with javascript"
  ./bin/validate_json.js "$name-php.json"
  echo "validating $name-php.json with PHP"
  ./bin/validate_json.php "$name-php.json"
done
