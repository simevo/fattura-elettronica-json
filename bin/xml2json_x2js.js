#!/usr/bin/nodejs
//
// converts the supplied XML invoice file to JSON usinf x2js library (works in browser too)
//
// Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
// License: BSD 3-Clause

"use strict";

var X2JS = require('x2js');
var fs = require('fs');

if (process.argv.length <= 2) {
    console.log("Usage: xml2json.js file.xml");
    process.exit(-1);
}

var filename = process.argv[2];

fs.readFile(filename, 'utf8', function (err, xml) {
  if (err) {
    return console.log(err);
  }
  var x2js = new X2JS();
  var json = x2js.xml2js(xml);
  if (typeof json.FatturaElettronica.FatturaElettronicaBody === 'object' && json.FatturaElettronica.FatturaElettronicaBody !== null) {
    json.FatturaElettronica.FatturaElettronicaBody = [ json.FatturaElettronica.FatturaElettronicaBody ]
  }
  json.FatturaElettronica.FatturaElettronicaBody.forEach(function (feb) {
    if (typeof feb.DatiBeniServizi.DatiRiepilogo === 'object' && feb.DatiBeniServizi.DatiRiepilogo !== null)
      feb.DatiBeniServizi.DatiRiepilogo = [ feb.DatiBeniServizi.DatiRiepilogo ]
    if (typeof feb.DatiPagamento === 'object' && feb.DatiPagamento !== null)
      feb.DatiPagamento = [ feb.DatiPagamento ]
  })
  delete json.FatturaElettronica.Signature;

  console.log("%s", JSON.stringify(json, null, 2));
});
