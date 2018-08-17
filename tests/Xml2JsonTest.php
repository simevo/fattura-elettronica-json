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

    public function testInvalidateSampleMissingCedentePrestatore(): void
    {
        $this->validateSample('invalid/missing_CedentePrestatore.xml', false);
    }

    public function testInvalidateSampleMissingCessionarioCommittente(): void
    {
        $this->validateSample('invalid/missing_CessionarioCommittente.xml', false);
    }

    public function testInvalidateSampleMissingCodiceDestinatario(): void
    {
        $this->validateSample('invalid/missing_CodiceDestinatario.xml', false);
    }

    public function testInvalidateSampleMissingDatiBeniServizi(): void
    {
        $this->validateSample('invalid/missing_DatiBeniServizi.xml', false);
    }

    public function testInvalidateSampleMissingDatiGeneraliDocumentoData(): void
    {
        $this->validateSample('invalid/missing_DatiGeneraliDocumento_Data.xml', false);
    }

    public function testInvalidateSampleMissingDatiGeneraliDocumentoDivisa(): void
    {
        $this->validateSample('invalid/missing_DatiGeneraliDocumento_Divisa.xml', false);
    }

    public function testInvalidateSampleMissingDatiGeneraliDocumentoNumero(): void
    {
        $this->validateSample('invalid/missing_DatiGeneraliDocumento_Numero.xml', false);
    }

    public function testInvalidateSampleMissingDatiGeneraliDocumentoTipoDocumento(): void
    {
        $this->validateSample('invalid/missing_DatiGeneraliDocumento_TipoDocumento.xml', false);
    }

    public function testInvalidateSampleMissingDatiGeneraliDocumento(): void
    {
        $this->validateSample('invalid/missing_DatiGeneraliDocumento.xml', false);
    }

    public function testInvalidateSampleMissingDatiGenerali(): void
    {
        $this->validateSample('invalid/missing_DatiGenerali.xml', false);
    }

    public function testInvalidateSampleMissingDatiTrasmissione(): void
    {
        $this->validateSample('invalid/missing_DatiTrasmissione.xml', false);
    }

    public function testInvalidateSampleMissingFatturaElettronicaBody(): void
    {
        $this->validateSample('invalid/missing_FatturaElettronicaBody.xml', false);
    }

    public function testInvalidateSampleMissingFatturaElettronicaHeader(): void
    {
        $this->validateSample('invalid/missing_FatturaElettronicaHeader.xml', false);
    }

    public function testInvalidateSampleMissingFormatoTrasmissione(): void
    {
        $this->validateSample('invalid/missing_FormatoTrasmissione.xml', false);
    }

    public function testInvalidateSampleMissingIdTrasmittenteIdCodice(): void
    {
        $this->validateSample('invalid/missing_IdTrasmittente_IdCodice.xml', false);
    }

    public function testInvalidateSampleMissingIdTrasmittenteIdPaese(): void
    {
        $this->validateSample('invalid/missing_IdTrasmittente_IdPaese.xml', false);
    }

    public function testInvalidateSampleMissingIdTrasmittente(): void
    {
        $this->validateSample('invalid/missing_IdTrasmittente.xml', false);
    }

    public function testInvalidateSampleMissingProgressivoInvio(): void
    {
        $this->validateSample('invalid/missing_ProgressivoInvio.xml', false);
    }
}
