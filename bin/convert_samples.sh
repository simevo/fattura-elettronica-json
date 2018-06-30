#!/bin/sh
#
# converts to JSON the sample XML invoice files from fatturapa.gov.it
#
# Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
# License: BSD 3-Clause

set -e

for name in "samples/IT01234567890_FPA01" "samples/IT01234567890_FPA02" "samples/IT01234567890_FPA03" "samples/IT01234567890_FPR01" "samples/IT01234567890_FPR02" "samples/IT01234567890_FPR03"
do
  echo "converting $name"
  ./bin/xml2json.js "$name" | grep -v 'xmlns:' | grep -v 'xsi:' | sed 's/p:FatturaElettronica/FatturaElettronica/g' > "$name.json"
done
