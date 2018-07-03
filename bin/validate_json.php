#!/usr/bin/php
<?php
// validates the supplied JSON invoice file against the JSON invoice schema
//
// Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
// License: BSD 3-Clause

require_once './vendor/autoload.php';

if (count($argv) <= 1) {
    echo "Usage: validate.php file.json\n";
    exit(-1);
}

$filename = $argv[1];
$data = json_decode(file_get_contents($filename));
$validator = new JsonSchema\Validator;
$validator->validate($data, (object)['$ref' => 'file://' . realpath('fatturaPA_1.2_schema.json')]);

if ($validator->isValid()) {
    echo "The supplied JSON validates against the schema.\n";
} else {
    echo "JSON does not validate. Violations:\n";
    foreach ($validator->getErrors() as $error) {
        echo sprintf("[%s] %s\n", $error['property'], $error['message']);
    }
}
