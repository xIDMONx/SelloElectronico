<?php

use Carbon\Carbon;
use xIDMONx\SEI\SignatureDTO;
use xIDMONx\SEI\SelloElectronico;
use GuzzleHttp\Exception\GuzzleException;

require '../vendor/autoload.php';

$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<documento tipo="activacionFisicaParticulares_aprobado1.2" version="1.2" emisor="gestionInterna" urlEmisor="http://gestion.edomex.gob.mx/gestion">
  <encabezado>
    <dtm_fecha_creacion>a 13 de Septiembre de 2021</dtm_fecha_creacion>
    <sn_folio>Activación Física 1/2021</sn_folio>
  </encabezado>
  <cuerpo>
    <ln_texto>&lt;p&gt;Por medio del presente le informo que, el Departamento de Cultura Fisica requiere una peticion por escrito para dar atencion a la misma, en la cual se comente, el apoyo requerido, dia, horario y espacio donde se llevara a cabo.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Si mas por el momento, quedo de Usted.&lt;/p&gt;</ln_texto>
  <remitentes>
    <remitente>
      <sn_etiqueta>A T E N T A M E N T E</sn_etiqueta>
      <ln_nombre_firmante>Jefe Depto.</ln_nombre_firmante>
      <ln_perfil>Jefe Depto.</ln_perfil>
      <cuts></cuts>
    </remitente>
  </remitentes>
</cuerpo>
</documento>
XML;

$sello = new SelloElectronico();

try {
    //Usuario para Conectarse el Web Service
    $sello->user = 'TLCImCUFIDET';
    $sello->password = 'JbP7h5&2Bt';
    
    $sello->cutsFirmante = 'ZIRL730819HMCMMS00';//CUTS del firmante
    $sello->paswFirmante = 'mapache73';//Password del firmante
    
    $signatureDTO = new SignatureDTO();
    $signatureDTO->setSubject( 'firma' );//Titulo
    $signatureDTO->setXml( base64_encode( $xml ) );//Cadena que contiene XML
    $signatureDTO->setApplicant( 'xIDMONx' );
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
    $save = $sello->savePDF( $url, '../', 'example.pdf' );
    
    if ( $save ) {
        echo "Archivo guardado";
    } else {
        echo "Archivo no guardado";
    }
} catch ( GuzzleException | Exception $e ) {
    echo $e->getMessage();
}
