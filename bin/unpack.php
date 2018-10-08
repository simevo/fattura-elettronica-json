#!/usr/bin/php
<?php
// unpacks the invoice and extracts some data
//
// Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
// License: BSD 3-Clause

require_once './vendor/autoload.php';

if (count($argv) <= 1) {
    echo "Usage: uppack.php file.xml\n";
    exit(-1);
}

$filename = $argv[1];

// defend against XML External Entity Injection
libxml_disable_entity_loader(true);
if (!file_exists($filename)) {
    throw new \InvalidArgumentException('File not found');
}
$xml_string = file_get_contents($filename);
$collapsed_xml_string = preg_replace("/\s+/", "", $xml_string);
$collapsed_xml_string = $collapsed_xml_string ? $collapsed_xml_string : $xml_string;
if (preg_match("/\<!DOCTYPE/i", $collapsed_xml_string)) {
    throw new \InvalidArgumentException('Invalid XML: Detected use of illegal DOCTYPE');
}

libxml_use_internal_errors(true);
$xml = simplexml_load_string($xml_string, 'SimpleXMLElement', LIBXML_NOWARNING);
if ($xml === false) {
    throw new \InvalidArgumentException("Cannot load XML\n");
}

echo $xml->FatturaElettronicaHeader->CedentePrestatore->DatiAnagrafici->IdFiscaleIVA->IdPaese . '-' . $xml->FatturaElettronicaHeader->CedentePrestatore->DatiAnagrafici->IdFiscaleIVA->IdCodice;
var_dump($xml->FatturaElettronicaBody[0]->DatiGenerali->DatiGeneraliDocumento->Data);