#!/usr/bin/nodejs
//
// converts the supplied XML file to JSON
//
// Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
// License: BSD 3-Clause

"use strict";

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
  var options = {
    object: true,
    arrayNotation: [
      "FatturaElettronicaBody",
      "Causale",
      "DatiOrdineAcquisto",
      "RiferimentoNumeroLinea",
      "DatiContratto",
      "DatiConvenzione",
      "DatiRicezione",
      "DettaglioLinee",
      "DatiRiepilogo",
      "DatiPagamento"
    ]
  };
  var json = parser.toJson(xml, options);
  for (var key in json) {
    json.FatturaElettronica = json[key];
    for (var subkey in json[key]) {
      if (subkey.indexOf(':') != -1) {
        delete json[key][subkey];
      }
    }
    delete json[key];
    break;
  }
  console.log("%s", JSON.stringify(json, null, 2));
});
