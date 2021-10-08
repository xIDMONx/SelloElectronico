## Gobierno del Estado de México

Dirección General del Sistema Estatal de Informática

### Generación de Firma Electrónica

## Instalación

Use el administrador de paquetes composer para instalar sei-sello-electronico.

```bash
composer require xidmonx/sei-sello-electronico
```

## Implementación

```php
<?php

use Carbon\Carbon;
use xIDMONx\SEI\SignatureDTO;
use xIDMONx\SEI\SelloElectronico;
use GuzzleHttp\Exception\GuzzleException;

require 'vendor/autoload.php';

$sello = new SelloElectronico();
//Usuario para Conectarse el Web Service
$sello->user = '';
$sello->password = '';

$sello->cutsFirmante = '';//CUTS del firmante
$sello->paswFirmante = '';//Password del firmante

$signatureDTO = new SignatureDTO();
$signatureDTO->setSubject( 'firma' );//Titulo
$signatureDTO->setXml( base64_encode( $xml ) );//Cadena que contiene XML
$signatureDTO->setApplicant( 'xIDMONx' );//Nombre del Aplicante
$signatureDTO->setDocumentName( 'Constancia.pdf' );//Nombre del archivo PDF
$signatureDTO->setRequestDate( Carbon::now() );//Fecha actual
$signatureDTO->setApplication( 'FirmaElectronicaConstancias' );  //Nombre aplicación
$signatureDTO->setCuts( $sello->cutsFirmante );
$signatureDTO->setSigners( [
    $sello->cutsFirmante,
    $sello->paswFirmante,
] );

echo "solicitaFirma<br>";
$hash = $sello->solicitaFirmaBatch( $signatureDTO );
echo "getCMS<br>";
$cms  = $sello->getCMS( $hash );
echo "getPDF<br>";
$url  = $sello->getPDF( $cms );
echo "savePDF<br>";
$save = $sello->savePDF( $url, '/', 'example.pdf' );

if ( $save ) {
    echo "Archivo guardado";
} else {
    echo "Archivo no guardado";
}

?>
```

## ¿Aun en desarrollo?

Sobrescribe las siguientes variables para apuntar al ambiente de desarrollo

```php
<?php
use Carbon\Carbon;
use xIDMONx\SEI\SignatureDTO;
use xIDMONx\SEI\SelloElectronico;
use GuzzleHttp\Exception\GuzzleException;

require 'vendor/autoload.php';

$sello = new SelloElectronico();
$sello->endpoint = $sello::WSDL_DESARROLLO;
$sello->validador_base = $sello::VALIDADOR_BASE_DESARROLLO;
...
?>
```

## Ejemplos de implementación

* [Única Firma](examples/single.php)
* [Multiples Firmas](examples/multiple)
