#!/bin/sh
#
# 1. generates 10 randomized JSON invoice files
# 2. validates them against the JSON invoice schema
# 3. converts them to XML
# 4. validates the resulting XML files against the XML invoice schema
#
# Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
# License: BSD 3-Clause

set -e

for i in $(seq 0 1 9)
do
  name=$(printf %02d "$i")
  echo "generating $name.json"
  bin/fake.js > "random/$name.json"
  echo "validate $name.json"
  bin/validate_json.js "random/$name.json"
  echo "convert to $name.xml"
  bin/hbs.js "random/$name.json" > "random/$name.xml"
  # echo "validate $name.xml"
  # xmllint --debug --schema Schema_del_file_xml_FatturaPA_versione_1.2_cleanup.xsd "random/$name.xml" -noout
done
