<?php
// sample usage:
// curl -X POST -F 'xml=@samples/IT01234567890_FPA01.xml' http://localhost:8000/xml2json.php

declare(strict_types=1);

require("Xml2Json.php");

$obj = new Simevo\Xml2Json($_FILES['xml']['tmp_name']);
echo $obj->result();
