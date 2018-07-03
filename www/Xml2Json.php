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

    private static function has_string_keys(array $array) {
        // https://stackoverflow.com/a/4254008
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    private static function normalize(&$node)
    {
        if (is_array($node)) {
            foreach ($node as $key => $value) {
                // echo "looking at $key of type " . gettype($node[$key]) . " with " . count($value) . " nodes\n";
                self::normalize($node[$key]);
                if (is_string($key) && in_array($key, self::$arrayNotation) && (!is_array($node[$key]) || self::has_string_keys($node[$key]))) {
                    // echo "converting $key\n";
                    $node[$key] = [$node[$key]];
                }
            }
        }
    }

    public function __construct(string $filename)
    {
        libxml_use_internal_errors(true);
        $this->xml = simplexml_load_file($filename, 'SimpleXMLElement', LIBXML_NOWARNING);
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
