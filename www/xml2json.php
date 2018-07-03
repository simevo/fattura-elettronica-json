<?php
// sample usage:
// curl -X POST -F 'xml=@samples/IT01234567890_FPA01.xml' http://localhost:8000/xml2json.php

function normalize(&$array, $arrayNotation)
{
    foreach ($array as $key => $value) {
        normalize($array[$key], $arrayNotation);
        if (in_array($key, $arrayNotation) && is_array($value)) {
            $array[$key] = [$array[$key]];
        }
    }
}

$arrayNotation = [
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
];

$xml = simplexml_load_file($_FILES['xml']['tmp_name']);
// convert back and forth to transform SimpleXMLElement Objects to associative arrays
$json = json_encode($xml);
$array = json_decode($json, true);
// recusively traverse array and convert selected scalar values to arrays
normalize($array, $arrayNotation);
// convert @attributes to keys
foreach ($array['@attributes'] as $key => $value) {
    $array[$key] = $value;
}
unset($array['@attributes']);
echo json_encode(array("FatturaElettronica" => $array), JSON_PRETTY_PRINT);
