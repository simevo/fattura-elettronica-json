#!/bin/sh
#
# validates against the XML invoice schema the sample XML invoice files from
# fatturapa.gov.it
#
# Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
# License: BSD 3-Clause

set -e

for name in "samples/IT01234567890_FPA01.xml" "samples/IT01234567890_FPA02.xml" "samples/IT01234567890_FPA03.xml" "samples/IT01234567890_FPR01.xml" "samples/IT01234567890_FPR02.xml" "samples/IT01234567890_FPR03.xml"
do
  echo "validating $name"
  ./bin/validate_xml.sh "$name"
done
