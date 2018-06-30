#!/bin/sh
#
# 1. converts the sample JSON invoice files to XML
# 2. validates them
# 3. and compares them to the original files
#
# Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
# License: BSD 3-Clause

for name in "IT01234567890_FPA01" "IT01234567890_FPA02" "IT01234567890_FPA03" "IT01234567890_FPR01" "IT01234567890_FPR02" "IT01234567890_FPR03"
do
  echo "reconverting $name"
  ./bin/hbs.js "samples/$name.json" > "$name.xml"
  xmllint --debug --schema Schema_del_file_xml_FatturaPA_versione_1.2_cleanup.xsd "$name.xml" -noout
  diff "samples/$name.xml" "$name.xml"
  rm "$name.xml"
done
