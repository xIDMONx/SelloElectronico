## Implementación para más de una firma en el mismo documento

```php
<?php

use Carbon\Carbon;
use xIDMONx\SEI\SignatureDTO;
use xIDMONx\SEI\SelloElectronico;
use GuzzleHttp\Exception\GuzzleException;

require '../vendor/autoload.php';

$sello = new SelloElectronico();

//Usuario para Conectarse el Web Service
$sello->user     = '';
$sello->password = '';

$sello->cutsFirmante = '';//CUTS del firmante
$sello->paswFirmante = '';//Password del firmante

$signatureDTO = new SignatureDTO();
$signatureDTO->setSubject( 'firma' );//Titulo
$signatureDTO->setXml( base64_encode( $xml ) );//Cadena que contiene XML
$signatureDTO->setApplicant( 'xIDMONx' );//Nombre del aplicante
$signatureDTO->setDocumentName( 'LicenciaDeConstruccion.pdf' );//Nombre del archivo PDF
$signatureDTO->setRequestDate( Carbon::now() );//Fecha actual
$signatureDTO->setApplication( 'FirmaElectronicaDesarrolloUrbano' );//Nombre aplicación
$signatureDTO->setCuts( $sello->cutsFirmante );
$signatureDTO->setSigners( [
    $sello->cutsFirmante,
    $sello->paswFirmante,
] );

//Solicitamos la firma del documento
$hash = $sello->solicitaFirmaBatchSHA2( $signatureDTO );

if ( empty( $hash ) ) {
    throw new Exception( "No se obtuvo el hash." );
}
//Guardamos el resultado en un archivo XML
$save = $sello->saveXML( '../', $hash );

if ( $save ) {
    echo "Archivo guardado";
} else {
    echo "Archivo no guardado";
}
?>
```

## Resultado

```xml
<?xml version="1.0" encoding="UTF-8"?>
<signinginfo>
	<document id="..." name="..."/>
	<ca name="...">
		<signatory isinvalidity="..." isrevoked="..." name="..." role="..." serialname="..." serialnum="...">
			<signature algorithm="..." date="..." localdate="..." status="...">...</signature>
			<ocsp responderissuer="..." respondername="..." responsedate="..." responselocaldate="..." revocationstatus="..." serialnum="..."/>
			<tsp digest="..." responderissuer="..." respondername="..." responsedate="..." responselocaldate="..." sequentialnum="..."/>
		</signatory>
	</ca>
</signinginfo>
```

## 

