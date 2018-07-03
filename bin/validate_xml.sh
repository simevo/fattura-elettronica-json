#!/bin/sh
#
# validates against the XML invoice schema the supplied XML invoice file
#
# Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
# License: BSD 3-Clause

set -e

xmllint --debug --schema Schema_del_file_xml_FatturaPA_versione_1.2_cleanup.xsd "$1" -noout
