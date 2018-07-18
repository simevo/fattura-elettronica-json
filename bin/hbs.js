#!/usr/bin/nodejs
//
// converts the JSON invoice data retrieved from the supplied file
// to XML using the Handlebars XML invoice template and
//
// Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
// License: BSD 3-Clause

"use strict";

var fs = require('fs');

if (process.argv.length <= 2) {
    console.log("Usage: hbs fattura.json");
    process.exit(-1);
}

var Handlebars = require('handlebars');
var source = fs.readFileSync('fatturaPA_1.2.hbs', 'utf-8');
var template = Handlebars.compile(source);
var filename = process.argv[2];
var context = loadJson(filename);
var xml = template(context);
console.log(xml);

function loadJson(path) {
  var data = fs.readFileSync(path, 'utf-8');
  var json = JSON.parse(data);
  return json;
}
