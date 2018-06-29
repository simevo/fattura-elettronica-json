#!/usr/bin/nodejs

var path = require('path');
var fs = require('fs');
var validate = require('json-schema/lib/validate').validate;

var schema = loadJson('www/fatturaPA_1.2_schema_semplificato.json');
files = [
  'samples/IT01234567890_FPA01.json',
  'samples/IT01234567890_FPA02.json',
  'samples/IT01234567890_FPA03.json',
  'samples/IT01234567890_FPR01.json',
  'samples/IT01234567890_FPR02.json',
  'samples/IT01234567890_FPR03.json'
];
files.forEach(function(file) {
  console.log('validating:', file);
  var doc = loadJson(file);
  var result = validate(doc, schema);
  console.log(result);
});

function loadJson(path) {
  var data = fs.readFileSync(path, 'utf-8');
  var json = JSON.parse(data);
  return json;
}
