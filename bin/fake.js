#!/usr/bin/nodejs
//
// generates a randomized JSON invoice file compliant with the JSON schema
//
// Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
// License: BSD 3-Clause

"use strict";

var fs = require('fs');
var jsf = require('json-schema-faker');

jsf.option({
  optionalsProbability: 0.7
});
var schema = loadJson('www/fatturaPA_1.2_schema.json');
jsf.resolve(schema).then(function(sample) {
  console.log(JSON.stringify(sample, null, 2));
});

function loadJson(path) {
  var data = fs.readFileSync(path, 'utf-8');
  var json = JSON.parse(data);
  return json;
}
