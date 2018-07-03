#!/usr/bin/php
<?php
// converts the supplied XML invoice file to JSON
//
// Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
// License: BSD 3-Clause

declare(strict_types=1);

require_once './vendor/autoload.php';
require("www/Xml2Json.php");

if (count($argv) <= 1) {
    echo "Usage: xml2json.php file.xml\n";
    exit(-1);
}

$filename = $argv[1];

$obj = new Simevo\Xml2Json($filename);
echo $obj->result();
