#!/usr/bin/nodejs

var parser = require('xml2json');
var fs = require('fs');

if (process.argv.length <= 2) {
    console.log("Usage: json2xml file.json");
    process.exit(-1);
}

var filename = process.argv[2];

fs.readFile(filename, 'utf8', function (err, json) {
  if (err) {
    return console.log(err);
  }
  var xml = parser.toXml(json);
  console.log("%s", xml);
});
