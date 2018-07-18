#!/usr/bin/php
<?php
// creates a nested array with incvoice data and converts it to XML
// based on samples/IT01234567890_FPR01-js.json
//
// Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
// License: BSD 3-Clause

declare(strict_types=1);

require_once './vendor/autoload.php';

# initialize the twig templating engine
$loader = new Twig_Loader_Filesystem('.');
$twig = new Twig_Environment($loader, array(
    'cache' => './tmp',
));
$template = $twig->load('fatturaPA_1.2.twig');

$fattura = array("FatturaElettronica" =>
  array(
    "versione" => "FPR12",
    "FatturaElettronicaHeader" => array(
      "DatiTrasmissione" => array(
        "IdTrasmittente" => array(
          "IdPaese" => "IT",
          "IdCodice" => "01234567890"
        ),
        "ProgressivoInvio" => "00001",
        "FormatoTrasmissione" => "FPR12",
        "CodiceDestinatario" => "ABC1234",
        "ContattiTrasmittente" => array()
      ),
      "CedentePrestatore" => array(
          "DatiAnagrafici" => array(
            "IdFiscaleIVA" => array(
              "IdPaese" => "IT",
              "IdCodice" => "01234567890"
            ),
            "Anagrafica" => array(
              "Denominazione" => "SOCIETA' ALPHA SRL"
            ),
            "RegimeFiscale" => "RF19"
          ),
          "Sede" => array(
            "Indirizzo" => "VIALE ROMA 543",
            "CAP" => "07100",
            "Comune" => "SASSARI",
            "Provincia" => "SS",
            "Nazione" => "IT"
          )
        ),
      "CessionarioCommittente" => array(
          "DatiAnagrafici" => array(
            "CodiceFiscale" => "09876543210",
            "Anagrafica" => array(
              "Denominazione" => "DITTA BETA"
            )
          ),
          "Sede" => array(
            "Indirizzo" => "VIA TORINO 38-B",
            "CAP" => "00145",
            "Comune" => "ROMA",
            "Provincia" => "RM",
            "Nazione" => "IT"
          )
      ),
    ),
    "FatturaElettronicaBody" => array(
      array(
        "DatiGenerali" => array(
          "DatiGeneraliDocumento" => array(
            "TipoDocumento" => "TD01",
            "Divisa" => "EUR",
            "Data" => "2014-12-18",
            "Numero" => "123",
            "Causale" => array(
              "LA FATTURA FA RIFERIMENTO AD UNA OPERAZIONE AAAA BBBBBBBBBBBBBBBBBB CCC DDDDDDDDDDDDDDD E FFFFFFFFFFFFFFFFFFFF GGGGGGGGGG HHHHHHH II LLLLLLLLLLLLLLLLL MMM NNNNN OO PPPPPPPPPPP QQQQ RRRR SSSSSSSSSSSSSS",
              "SEGUE DESCRIZIONE CAUSALE NEL CASO IN CUI NON SIANO STATI SUFFICIENTI 200 CARATTERI AAAAAAAAAAA BBBBBBBBBBBBBBBBB"
            )
          ),
          "DatiOrdineAcquisto" => array(
            array(
              "RiferimentoNumeroLinea" => array(
                "1"
              ),
              "IdDocumento" => "66685",
              "NumItem" => "1"
            )
          ),
          "DatiContratto" => array(
            array(
              "RiferimentoNumeroLinea" => array(
                "1"
              ),
              "IdDocumento" => "123",
              "Data" => "2012-09-01",
              "NumItem" => "5",
              "CodiceCUP" => "123abc",
              "CodiceCIG" => "456def"
            )
          ),
          "DatiTrasporto" => array(
            "DatiAnagraficiVettore" => array(
              "IdFiscaleIVA" => array(
                "IdPaese" => "IT",
                "IdCodice" => "24681012141"
              ),
              "Anagrafica" => array(
                "Denominazione" => "Trasporto spa"
              )
            ),
            "DataOraConsegna" => "2012-10-22T16:46:12.000+02:00"
          )
        ),
        "DatiBeniServizi" => array(
          "DettaglioLinee" => array(
            array(
              "NumeroLinea" => "1",
              "Descrizione" => "DESCRIZIONE DELLA FORNITURA",
              "Quantita" => "5.00",
              "PrezzoUnitario" => "1.00",
              "PrezzoTotale" => "5.00",
              "AliquotaIVA" => "22.00"
            )
          ),
          "DatiRiepilogo" => array(
            array(
              "AliquotaIVA" => "22.00",
              "ImponibileImporto" => "5.00",
              "Imposta" => "1.10",
              "EsigibilitaIVA" => "I"
            )
          )
        ),
        "DatiPagamento" => array(
          array(
            "CondizioniPagamento" => "TP01",
            "DettaglioPagamento" => array(
              "ModalitaPagamento" => "MP01",
              "DataScadenzaPagamento" => "2015-01-30",
              "ImportoPagamento" => "6.10"
            )
          )
        )
      )
    )
  )
);
$json = json_encode($fattura, JSON_PRETTY_PRINT);

// echo $json;
// validate with:
// ./bin/validate_json.js IT01234567890_FPR01.json
// ./bin/validate_json.php IT01234567890_FPR01.json

echo $template->render($fattura);
// validate with:
// ./bin/validate_xml.sh IT01234567890_FPR01.xml
