#!/bin/sh
#
# validates against the invoice JSON schema the sample XML invoice files from
# fatturapa.gov.it converted to JSON
#
# Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
# License: BSD 3-Clause

set -e

for name in "samples/IT01234567890_FPA01.json" "samples/IT01234567890_FPA02.json" "samples/IT01234567890_FPA03.json" "samples/IT01234567890_FPR01.json" "samples/IT01234567890_FPR02.json" "samples/IT01234567890_FPR03.json"
do
  echo "validating $name"
  ./bin/validate.js "$name"
done
