# Rappresentazione JSON per le fatture elettroniche

**Proof-of-concept** per una rappresentazione in formato JSON delle fatture elettroniche secondo la normativa italiana ("fatturaPA").

## Scopo

Le fatture elettroniche vengono trasmesse ed archiviate in formato **XML**.

La documentazione tecnica di riferimento del **formato XML** delle fatture elettroniche è disponibile al seguente url: http://www.fatturapa.gov.it/export/fatturazione/it/normativa/f-2.htm

Poiché per lo sviluppo di API ed SDK è più comodo manipolare i dati in formato **JSON**, questo proof-of-concept mira a valutare la fattibilità di:
- **convertire** XML <-> JSON
- **validare** JSON
- **generare** di file fattura JSON e XML **randomizzati** a scopo di test e fuzzy-test
- **rapid prototyping** di visualizzatori ed editor
- persistenza in **database** SQL e No-SQL.

## Prerequisiti

```
sudo apt install make nodejs yarnpkg python libxml2-utils
yarnpkg install
```

## Procedura

La procedura seguita per il proof-of-concept è rappresentata nel seguente flow-chart (in blu i files tratti dal sito `fatturapa.gov.it`, in rosso quelli generati in questo progetto):

![Flow-chart](flowchart.png "Flow-chart del proof-of-concept")

Innanzitutto lo [schema XML (*XSD**)](https://en.wikipedia.org/wiki/XML_Schema_(W3C)) della fattura elettronica tratto dal sito `fatturapa.gov.it` ([`Schema_del_file_xml_FatturaPA_versione_1.2.xsd`](Schema_del_file_xml_FatturaPA_versione_1.2.xsd)) è stato trasformato in una versione ([`Schema_del_file_xml_FatturaPA_versione_1.2_cleanup.xsd`](Schema_del_file_xml_FatturaPA_versione_1.2_cleanup.xsd)) normalizzata (fromdos, xmllint, 2-space indent) e che importa una [copia locale dello schema `xmldsig`](xmldsig-core-schema.xsd):
```
-   <xs:import namespace="http://www.w3.org/2000/09/xmldsig#" schemaLocation="http://www.w3.org/TR/2002/REC-xmldsig-core-20020212/xmldsig-core-schema.xsd" />
+   <xs:import namespace="http://www.w3.org/2000/09/xmldsig#" schemaLocation="xmldsig-core-schema.xsd"/>
```

Quindi i files XML di esempio tratti dal sito `fatturapa.gov.it` sono stati validati con questo schema così modificato (script [`bin/validate_samples.sh`](bin/validate_samples.sh)).

Le fatture in formato JSON possono essere validate per mezzo di un **JSON schema** (vedi [paragrafo Riferimenti](#riferimenti)), anche se quest'ultimo è più limitato dello schema XML, alcuni vincoli dovranno essere validati dall'applicazione, ad esempio:

- limitazioni ai **set di caratteri ammessi**: ad esempio il campo `FatturaElettronica.FatturaElettronicaHeader.DatiTrasmissione.ProgressivoInvio` secondo lo schema XML è vincolato al tipo `String10Type` così definito:
    ```
    <xs:simpleType name="String10Type">
      <xs:restriction base="xs:normalizedString">
        <xs:pattern value="(\p{IsBasicLatin}{1,10})"/>
      </xs:restriction>
    </xs:simpleType>
    ```
  che accetta da 1 a 10 caratteri del [blocco Unicode Basic Latin](https://www.w3.org/TR/xsd-unicode-blocknames/), mentre lo schema JSON accetta qualunque stringa di lunghezza compresa tra 1 e 10 caratteri:
    ```
    "ProgressivoInvio": {
      "type": "string",
      "minLength": 1,
      "maxLength": 10,
    ```
- **alternative**: ad esempio il tipo `AnagraficaType` usato per i campi:
  - `FatturaElettronica.FatturaElettronicaHeader.CedentePrestatore.DatiAnagrafici.Anagrafica`,
  - `FatturaElettronica.FatturaElettronicaBody[].DatiTrasporto.DatiAnagraficiVettore.Anagrafica`
  - `FatturaElettronica.FatturaElettronicaHeader.RappresentanteFiscale.DatiAnagraficiRappresentante.Anagrafica`
  - `FatturaElettronica.FatturaElettronicaHeader.CessionarioCommittente.DatiAnagraficiCessionario.Anagrafica`
  - `FatturaElettronica.FatturaElettronicaHeader.TerzoIntermediarioOSoggettoEmittente.DatiAnagraficiTerzoIntermediario.Anagrafica`

  contiene un campo `Denominazione` che è in alternativa a `Nome` + `Cognome`; non vi è modo di esprimere questo tipo di vincolo in JSON schema.

Inizialmente si è tentato di convertire lo schema XSD in uno [schema JSON](http://json-schema.org/) usando la libreria [jgeXml](https://github.com/Mermade/jgeXml):
```
nodejs ./node_modules/jgexml/testxsd2j.js Schema_del_file_xml_FatturaPA_versione_1.2.xsd  > fatturaPA_1.2_schema.json
```
ottenendo però uno schema non utilizzabile.

Successivamente i files XML di esempio sono stati convertiti a JSON per mezzo della libreria [node-xml2json](https://github.com/buglabs/node-xml2json) (script [`bin/convert_samples.sh`](bin/convert_samples.sh)).

Questa conversione automatica funziona più o meno, ma c'è un intoppo per quegli elementi che possono ripetersi (ad esempio `FatturaElettronica.FatturaElettronicaBody` e `FatturaElettronica.FatturaElettronicaBody[1].DatiBeniServizi.DettaglioLinee`), nel caso ve ne sia uno solo la conversione genera un JSON dove invece di un `array` c'è un `object` vedi:
- https://social.msdn.microsoft.com/Forums/windowsapps/en-US/03fe9c57-b7a8-41aa-ade4-652b6b5eafc3/single-element-xml-to-json-array
- http://jersey.576304.n2.nabble.com/Single-Element-Arrays-and-JSON-td5532105.html

Per questi casi occorre wrappare manualmente l'`object` con `[]`, vedi punto 1 del [paragrafo **TODO**](#todo).

Si è quindi generato uno schema a partire dai files fattura JSON di esempio, per mezzo del servizio on-line [jsonschema.net](https://www.jsonschema.net/), che dopo semplificazione (`grep -v '$id'`) e aggiustamento manuale aggiunta di campi `title` e `description` desunti dalle SPECIFICHE TECNICHE OPERATIVE DEL FORMATO DELLA FATTURA DEL SISTEMA DI INTERSCAMBIO ha dato origine allo schema [`fatturaPA_1.2_schema.json`](fatturaPA_1.2_schema.json).

Tutti i files di esempio JSON sono validati dallo schema (script [`bin/validate_samples_json.sh`](bin/validate_samples_json.sh)).

La generazione di files XML random a scopo di test è possibile con alcuni strumenti commerciali, ad esempio:
- https://msdn.microsoft.com/en-us/library/aa302296.aspx "Generating XML Documents from XML Schemas" Priya Lakshminarayanan, Microsoft Corporation - August 2004
- https://msdn.microsoft.com/en-us/library/dd489258.aspx "How to: Create an XML Document Based on an XSD Schema Visual Studio 2015"
- https://msdn.microsoft.com/en-us/library/cc716766.aspx "XML Schema Explorer"

In questo proof-of-concept si è scelto di generare dei files JSON random partendo dallo schema JSON per mezzo di [json-schema-faker](https://github.com/json-schema-faker/json-schema-faker), e di riconvertirli a XML per mezzo del template [handlebars](http://handlebarsjs.com/) [`fatturaPA_1.2.hbs`](fatturaPA_1.2.hbs) (script [`bin/generate_random.sh`](bin/generate_random.sh)).

Il template handlebars è stato validato riconvertendo ad XML i files di esempio (script [`bin/reconvert_samples.sh`](bin/reconvert_samples.sh)).

Al momento tuttavia i files XML così ottenuti non passano la validazione con lo schema XML, con degli errori del tipo:
```
random/00.xml:21: element Nome: Schemas validity error : Element 'Nome': This element is not expected. Expected is one of ( Titolo, CodEORI ).
random/00.xml:23: element RegimeFiscale: Schemas validity error : Element 'RegimeFiscale': [facet 'enumeration'] The value 'RF33' is not an element of the set {'RF01', 'RF02', 'RF03', 'RF04', 'RF05', 'RF06', 'RF07', 'RF08', 'RF09', 'RF10', 'RF11', 'RF12', 'RF13', 'RF14', 'RF15', 'RF16', 'RF17', 'RF19', 'RF18'}.
random/00.xml:23: element RegimeFiscale: Schemas validity error : Element 'RegimeFiscale': 'RF33' is not a valid value of the atomic type '{http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2}RegimeFiscaleType'.
random/00.xml:50: element TipoDocumento: Schemas validity error : Element 'TipoDocumento': [facet 'enumeration'] The value 'TD24' is not an element of the set {'TD01', 'TD02', 'TD03', 'TD04', 'TD05', 'TD06'}.
random/00.xml:50: element TipoDocumento: Schemas validity error : Element 'TipoDocumento': 'TD24' is not a valid value of the atomic type '{http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2}TipoDocumentoType'.
random/00.xml:52: element Data: Schemas validity error : Element 'Data': '6591-35-44' is not a valid value of the atomic type '{http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2}DataFatturaType'.
random/00.xml:107: element TipoDocumento: Schemas validity error : Element 'TipoDocumento': [facet 'enumeration'] The value 'TD74' is not an element of the set {'TD01', 'TD02', 'TD03', 'TD04', 'TD05', 'TD06'}.
random/00.xml:107: element TipoDocumento: Schemas validity error : Element 'TipoDocumento': 'TD74' is not a valid value of the atomic type '{http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2}TipoDocumentoType'.
random/00.xml:109: element Data: Schemas validity error : Element 'Data': '5281-04-38' is not a valid value of the atomic type '{http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2}DataFatturaType'.
random/00.xml:160: element TipoDocumento: Schemas validity error : Element 'TipoDocumento': [facet 'enumeration'] The value 'TD85' is not an element of the set {'TD01', 'TD02', 'TD03', 'TD04', 'TD05', 'TD06'}.
random/00.xml:160: element TipoDocumento: Schemas validity error : Element 'TipoDocumento': 'TD85' is not a valid value of the atomic type '{http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2}TipoDocumentoType'.
random/00.xml:162: element Data: Schemas validity error : Element 'Data': '8927-46-70' is not a valid value of the atomic type '{http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2}DataFatturaType'.
random/00.xml fails to validate
```
causati dalla eccessiva randomizzazione, vedi punto 2 del [paragrafo **TODO**](#todo).

## Demo

Con lo schema così ottenuto e [JSON Editor](https://github.com/json-editor/json-editor) è possibile generare automaticamente un editor basico.

Per lanciare il demo:
```
make
python -m SimpleHTTPServer
```
quindi visitare http://localhost:8000/www/index.html

## TODO

0. Completare lo schema JSON

1. implementare la conversione automatica da fattura formato XML a formato JSON; dovrebbe essere possibile configurando xml2json con l'[opzione `arrayNotation`](https://github.com/buglabs/node-xml2json#options-object-for-tojson)

2. configurare `json-schema-faker` [annotando lo schema](https://github.com/json-schema-faker/json-schema-faker#faking-values) in modo che i files JSON dopo conversione a XML passino la validazione

3. aggiungere funzioni al demo usando [le estensioni allo schema JSON specifiche di JSON Editor](https://github.com/json-editor/json-editor#json-schema-support) e implementando le funzioni avanzate e lo styling come negli esempi [Advanced Usage](http://rawgithub.com/jdorn/json-editor/master/examples/advanced.html) e [CSS Integration](http://rawgithub.com/jdorn/json-editor/master/examples/css_integration.html)

4. testare editor basato su specifici front-end frameworks:

    - vue.js, esempio: https://gitlab.com/formschema/native

    - react, esempio: https://github.com/mozilla-services/react-jsonschema-form

    - angular, esempio: https://github.com/json-schema-form/angular-schema-form

## Legalese

Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>

Licenza: BSD 3-Clause, vedi file [`LICENSE`](LICENSE).

## Riferimenti

- https://jsonlint.com/

- http://json-schema.org/

- https://spacetelescope.github.io/understanding-json-schema/reference/index.html

- https://jsonschemalint.com/#/version/draft-06/markup/json
