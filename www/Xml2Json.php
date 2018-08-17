<?php
// sample usage:
// curl -X POST -F 'xml=@samples/IT01234567890_FPA01.xml' http://localhost:8000/xml2json.php

declare(strict_types=1);

namespace Simevo;

final class Xml2Json
{
    private static $arrayNotation = [
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

    private $xml;
    private $array;

    private static function hasStringKeys(array $array)
    {
        // https://stackoverflow.com/a/4254008
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    private static function normalize(&$node)
    {
        if (is_array($node)) {
            foreach ($node as $key => $value) {
                // echo "looking at $key of type " . gettype($node[$key]) . " with " . count($value) . " nodes\n";
                self::normalize($node[$key]);
                if (is_string($key) &&
                    in_array($key, self::$arrayNotation) &&
                    (!is_array($node[$key]) || self::hasStringKeys($node[$key]))) {
                    // echo "converting $key\n";
                    $node[$key] = [$node[$key]];
                }
            }
        }
    }

    public function __construct(string $filename)
    {
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
        $this->xml = simplexml_load_string($xml_string, 'SimpleXMLElement', LIBXML_NOWARNING);
        if ($this->xml === false) {
            throw new \InvalidArgumentException("Cannot load xml file.\n");
        }
        // convert back and forth to transform SimpleXMLElement Objects to associative arrays
        $json = json_encode($this->xml);
        $array = json_decode($json, true);
        // recusively traverse array and convert selected scalar values to arrays
        self::normalize($array);
        // convert @attributes to keys
        foreach ($array['@attributes'] as $key => $value) {
            $array[$key] = $value;
        }
        unset($array['@attributes']);
        $this->array = $array;
    }

    public function result()
    {
        return json_encode(array("FatturaElettronica" => $this->array), JSON_PRETTY_PRINT);
    }
}
