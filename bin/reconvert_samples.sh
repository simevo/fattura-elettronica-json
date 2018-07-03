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
  echo "reconverting $name-js"
  ./bin/hbs.js "samples/$name-js.json" > "$name-js.xml"
  # xmllint --debug --schema Schema_del_file_xml_FatturaPA_versione_1.2_cleanup.xsd "$name-js.xml" -noout
  diff "samples/$name.xml" "$name-js.xml"
  rm "$name-js.xml"

  echo "reconverting $name-php"
  ./bin/hbs.js "samples/$name-php.json" > "$name-php.xml"
  # xmllint --debug --schema Schema_del_file_xml_FatturaPA_versione_1.2_cleanup.xsd "$name-js.xml" -noout
  diff "samples/$name.xml" "$name-php.xml"
  rm "$name-php.xml"
done
