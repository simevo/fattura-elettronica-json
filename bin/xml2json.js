#!/usr/bin/nodejs

var parser = require('xml2json');
var fs = require('fs');

if (process.argv.length <= 2) {
    console.log("Usage: xml2json file.json");
    process.exit(-1);
}

var filename = process.argv[2];

fs.readFile(filename, 'utf8', function (err, xml) {
  if (err) {
    return console.log(err);
  }
  var json = parser.toJson(xml, {object: true});
  console.log("%s", JSON.stringify(json, null, 2));
});
