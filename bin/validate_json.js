#!/usr/bin/nodejs
//
// validates the supplied JSON invoice file against the JSON invoice schema
//
// Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
// License: BSD 3-Clause

"use strict";

var fs = require('fs');
var validate = require('json-schema/lib/validate').validate;

if (process.argv.length <= 2) {
    console.log("Usage: validate_json.js file.json");
    process.exit(-1);
}

var filename = process.argv[2];

var schema = loadJson('www/fatturaPA_1.2_schema.json');
console.log('validating:', filename);
var doc = loadJson(filename);
var result = validate(doc, schema);
console.log(result);

function loadJson(path) {
  var data = fs.readFileSync(path, 'utf-8');
  var json = JSON.parse(data);
  return json;
}
