<?php

use Carbon\Carbon;
use xIDMONx\SEI\SignatureDTO;
use xIDMONx\SEI\SelloElectronico;
use GuzzleHttp\Exception\GuzzleException;

require '../vendor/autoload.php';

$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<documento tipo="licenciaConstruccionObraNueva1.0" version="1.0" emisor="gestionInterna" urlEmisor="http://gestion.edomex.gob.mx/gestion">
  <encabezado>
    <sn_no_licencia>18061983</sn_no_licencia>
    <sn_folio_referencia>1200</sn_folio_referencia>
    <sn_referencia_lus>12345</sn_referencia_lus>
    <sn_zona>SUR</sn_zona>
    <ln_nombre>LICENCIA DE CONSTRUCCIóN HASTA 60 METROS (OBRA NUEVA)</ln_nombre>
    <destino_inmueble>HABITACIONAL</destino_inmueble>
  </encabezado>
  <cuerpo>
    <tramite_realizado>obra_nueva</tramite_realizado>
    <superficie_construida>60.00</superficie_construida>
    <fecha_expedicion>20 DE MAYO DE 2021</fecha_expedicion>
    <fecha_vencimiento>20 DE MAYO DE 2022</fecha_vencimiento>
    <superficie_construccion_predio>300.00</superficie_construccion_predio>
    <superficie_construccion_existente>0</superficie_construccion_existente>
    <predio>
      <predio_calle>JOSEFA ORTIZ DE DOMINGUEZ</predio_calle>
      <predio_no_oficial>79</predio_no_oficial>
      <predio_no_anterior/>
      <predio_del_subd>BENITO JUAREZ</predio_del_subd>
      <predio_utb/>
      <predio_clave_catastral>2837461527388384</predio_clave_catastral>
      <predio_mza/>
      <predio_lote/>
      <predio_sup>300.00</predio_sup>
    </predio>
    <propietario>
      <propietario_nombre>MARIAN ALVARADO RODRIGUEZ</propietario_nombre>
      <propietario_direccion>JOSEFA ORTIZ DE DOMINGUEZ</propietario_direccion>
      <propietario_col_del>CENTRO</propietario_col_del>
    </propietario>
    <director_obra>
      <director_obra_sn_nombre/>
      <director_obra_ln_domicilio_prof/>
      <director_obra_ln_colonia_delegacion/>
      <director_obra_ln_dir_obra/>
      <director_obra_sn_cedula_profesional/>
    </director_obra>
    <derechos>
      <derechos_ln_derechos>OBRA NUEVA $ 900.00</derechos_ln_derechos>
      <derechos_sn_total_derechos>$ 900.00</derechos_sn_total_derechos>
    </derechos>
    <otras_disposiciones>
      <disposiciones>&lt;p&gt;Buen dia, &lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Officio de permiso de obra nueva.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Saludos cordiales&lt;/p&gt;</disposiciones>
    </otras_disposiciones>
    <dictaminador>
      <lesl>MARGARITA RUIZ</lesl>
      <ssl>SOLEDAD RUBIO</ssl>
    </dictaminador>
</cuerpo>
</documento>
XML;

$sello = new SelloElectronico();

try {
    //Usuario para Conectarse el Web Service
    $sello->user     = '';
    $sello->password = '';
    
    $sello->cutsFirmante = '';//CUTS del firmante
    $sello->paswFirmante = '';//Password del firmante
    
    $signatureDTO = new SignatureDTO();
    $signatureDTO->setSubject( 'firma' );//Titulo
    $signatureDTO->setXml( base64_encode( $xml ) );//Cadena que contiene XML
    $signatureDTO->setApplicant( 'xIDMONx' );
    $signatureDTO->setDocumentName( 'LicenciaDeConstruccion.pdf' );//Nombre del archivo PDF
    $signatureDTO->setRequestDate( Carbon::now() );//Fecha actual
    $signatureDTO->setApplication( 'FirmaElectronicaDesarrolloUrbano' );//Nombre aplicación
    $signatureDTO->setCuts( $sello->cutsFirmante );
    $signatureDTO->setSigners( [
        $sello->cutsFirmante,
        $sello->paswFirmante,
    ] );
    
    $hash = $sello->solicitaFirmaBatchSHA2( $signatureDTO );
    
    if ( empty( $hash ) ) {
        throw new Exception( "No se obtuvo el hash." );
    }
    
    $save = $sello->saveXML( '../', $hash );
    
    if ( $save ) {
        echo "Archivo guardado";
    } else {
        echo "Archivo no guardado";
    }
    
} catch ( GuzzleException | Exception $e ) {
    echo $e->getMessage();
}
