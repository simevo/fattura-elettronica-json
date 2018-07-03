<?php
declare(strict_types=1);

require("www/Xml2Json.php");
require_once './vendor/autoload.php';

final class Xml2JsonTest extends PHPUnit\Framework\TestCase
{
    private $validator;

    public function testCanBeCreatedFromValidXmlFile(): void
    {
        $this->assertInstanceOf(
            Simevo\Xml2Json::class,
            new Simevo\Xml2Json('samples/IT01234567890_FPA01.xml')
        );
    }

    public function testCannotBeCreatedFromMissingFile(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Simevo\Xml2Json('invalid.xml');
    }

    public function testCannotBeCreatedFromMalformedFile(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Simevo\Xml2Json('invalid/malformed.xml');
    }


    private function validateSample($filename, $valid = true): void
    {
        $this->validator = new JsonSchema\Validator;
        $obj = new Simevo\Xml2Json($filename);
        $json = $obj->result();
        $data = json_decode($json);
        $this->validator->validate($data, (object)['$ref' => 'file://' . realpath('fatturaPA_1.2_schema.json')]);
        $this->assertEquals($this->validator->isValid(), $valid);
    }

    public function testValidateSampleFpa01(): void
    {
        $this->validateSample('samples/IT01234567890_FPA01.xml');
    }
    public function testValidateSampleFpa02(): void
    {
        $this->validateSample('samples/IT01234567890_FPA02.xml');
    }
    public function testValidateSampleFpa03(): void
    {
        $this->validateSample('samples/IT01234567890_FPA03.xml');
    }
    public function testValidateSampleFpr01(): void
    {
        $this->validateSample('samples/IT01234567890_FPR01.xml');
    }
    public function testValidateSampleFpr02(): void
    {
        $this->validateSample('samples/IT01234567890_FPR02.xml');
    }
    public function testValidateSampleFpr03(): void
    {
        $this->validateSample('samples/IT01234567890_FPR03.xml');
    }

    public function testInalidateSampleMissingCedentePrestatore(): void
    {
        $this->validateSample('invalid/missing_CedentePrestatore.xml', false);
    }

    public function testInalidateSampleMissingCessionarioCommittente(): void
    {
        $this->validateSample('invalid/missing_CessionarioCommittente.xml', false);
    }

    public function testInalidateSampleMissingCodiceDestinatario(): void
    {
        $this->validateSample('invalid/missing_CodiceDestinatario.xml', false);
    }

    public function testInalidateSampleMissingDatiBeniServizi(): void
    {
        $this->validateSample('invalid/missing_DatiBeniServizi.xml', false);
    }

    public function testInalidateSampleMissingDatiGeneraliDocumentoData(): void
    {
        $this->validateSample('invalid/missing_DatiGeneraliDocumento_Data.xml', false);
    }

    public function testInalidateSampleMissingDatiGeneraliDocumentoDivisa(): void
    {
        $this->validateSample('invalid/missing_DatiGeneraliDocumento_Divisa.xml', false);
    }

    public function testInalidateSampleMissingDatiGeneraliDocumentoNumero(): void
    {
        $this->validateSample('invalid/missing_DatiGeneraliDocumento_Numero.xml', false);
    }

    public function testInalidateSampleMissingDatiGeneraliDocumentoTipoDocumento(): void
    {
        $this->validateSample('invalid/missing_DatiGeneraliDocumento_TipoDocumento.xml', false);
    }

    public function testInalidateSampleMissingDatiGeneraliDocumento(): void
    {
        $this->validateSample('invalid/missing_DatiGeneraliDocumento.xml', false);
    }

    public function testInalidateSampleMissingDatiGenerali(): void
    {
        $this->validateSample('invalid/missing_DatiGenerali.xml', false);
    }

    public function testInalidateSampleMissingDatiTrasmissione(): void
    {
        $this->validateSample('invalid/missing_DatiTrasmissione.xml', false);
    }

    public function testInalidateSampleMissingFatturaElettronicaBody(): void
    {
        $this->validateSample('invalid/missing_FatturaElettronicaBody.xml', false);
    }

    public function testInalidateSampleMissingFatturaElettronicaHeader(): void
    {
        $this->validateSample('invalid/missing_FatturaElettronicaHeader.xml', false);
    }

    public function testInalidateSampleMissingFormatoTrasmissione(): void
    {
        $this->validateSample('invalid/missing_FormatoTrasmissione.xml', false);
    }

    public function testInalidateSampleMissingIdTrasmittenteIdCodice(): void
    {
        $this->validateSample('invalid/missing_IdTrasmittente_IdCodice.xml', false);
    }

    public function testInalidateSampleMissingIdTrasmittenteIdPaese(): void
    {
        $this->validateSample('invalid/missing_IdTrasmittente_IdPaese.xml', false);
    }

    public function testInalidateSampleMissingIdTrasmittente(): void
    {
        $this->validateSample('invalid/missing_IdTrasmittente.xml', false);
    }

    public function testInalidateSampleMissingProgressivoInvio(): void
    {
        $this->validateSample('invalid/missing_ProgressivoInvio.xml', false);
    }
}
