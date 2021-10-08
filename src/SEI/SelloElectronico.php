<?php

namespace xIDMONx\SEI;

use SoapVar;
use Exception;
use SoapClient;
use GuzzleHttp\Client;
use xIDMONx\SEI\Util\XMLSerializer;

/**
 * Class SelloElectronico
 *
 * @author  xIDMONx
 * @package xIDMONx\SEI
 */
class SelloElectronico
{
    const URI_CEROPAPEL = 'http://service.ceroPapel.dgsei.edomex.gob.mx/';
    
    const WSDL_DESARROLLO = 'http://qasistemas2.edomex.gob.mx/ceroPapel/webService?wsdl';
    const WSDL_PRODUCCION = 'http://ceromultas.edomex.gob.mx/ceroPapel/webService?wsdl';
    
    const VALIDADOR_BASE_DESARROLLO = 'http://desasei.edomex.gob.mx/';
    const VALIDADOR_BASE_PRODUCCION = 'http://servicios.edomex.gob.mx/';
    const VALIDADOR_URI = 'validador/cms/service/validate';
    const VALIDADOR_STATUS_SUCCESS = 'OK';
    const VALIDADOR_STATUS_ERROR = 'NOT_OK';
    
    /**
     * @var string
     */
    public $user = '';
    /**
     * @var string
     */
    public $password = '';
    /**
     * @var string
     */
    public $cutsFirmante = '';
    /**
     * @var string
     */
    public $paswFirmante = '';
    /**
     * @var string
     */
    public $validador_uri = self::VALIDADOR_URI;
    /**
     * @var string
     */
    public $validador_base = self::VALIDADOR_BASE_PRODUCCION;
    /**
     * @var string
     */
    public $xml = '';
    /**
     * @var string
     */
    public $endpoint = self::WSDL_PRODUCCION;
    
    public function __construct() { }
    
    /**
     * @return object
     * @throws \SoapFault
     */
    public function solicitaFirmaBatch( SignatureDTO $signatureDTO )
    {
        $documentoVO = new DocumentoVO();
        $documentoVO->setContenido( $signatureDTO->getXml() );
        $documentoVO->setNombre( $signatureDTO->getDocumentName() );
        
        $solicitudVO = new SolicitudVO();
        $solicitudVO->setAsunto( $signatureDTO->getSubject() );
        $solicitudVO->setListaOrdenada( true );
        $solicitudVO->setListaFirmantes( $signatureDTO->getSigners() );
        $solicitudVO->setDocumento( $documentoVO );
        $solicitudVO->setFechaSolicitud( $this->gregorianDate( $signatureDTO ) );
        $solicitudVO->setSolicitante( $signatureDTO->getApplicant() );
        $solicitudVO->setSistemaSolicitante( $signatureDTO->getApplication() );
        $solicitudVO->setFechaVencimiento( $this->gregorianDate( $signatureDTO ) );
        $solicitudVO->setTipoDocumento(1 );
        
        return $this->nuevaSolicitudBatch( $solicitudVO );
    }
    
    /**
     * @param \xIDMONx\SEI\SignatureDTO $signatureDTO
     * @return object
     * @throws \SoapFault
     */
    public function solicitaFirmaBatchSHA2( SignatureDTO $signatureDTO )
    {
        $documentoVO = new DocumentoVO();
        $documentoVO->setContenido( $signatureDTO->getXml() );
        $documentoVO->setNombre( $signatureDTO->getDocumentName() );
    
        $solicitudVO = new SolicitudVO();
        $solicitudVO->setAsunto( $signatureDTO->getSubject() );
        $solicitudVO->setListaOrdenada( true );
        $solicitudVO->setListaFirmantes( $signatureDTO->getSigners() );
        $solicitudVO->setDocumento( $documentoVO );
        $solicitudVO->setFechaSolicitud( $this->gregorianDate( $signatureDTO ) );
        $solicitudVO->setSolicitante( $signatureDTO->getApplicant() );
        $solicitudVO->setSistemaSolicitante( $signatureDTO->getApplication() );
        $solicitudVO->setFechaVencimiento( $this->gregorianDate( $signatureDTO ) );
        $solicitudVO->setTipoDocumento( 1 );
        
        return $this->nuevaSolicitudBatchSHA2( $solicitudVO );
    }
    
    /**
     * @param string $hash
     * @return string
     * @throws \SoapFault
     */
    public function getCMS( string $hash )
    : string
    {
        $documentoVO = new DocumentoVO();
        $documentoVO->setContenido( $this->obtenerDocumentoFirmado( $hash )->contenido );
        
        return $documentoVO->getContenido();
    }
    
    /**
     * @param string $cms
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getPDF( string $cms )
    : string
    {
        $config = [
            'base_uri' => $this->validador_base,
            'verify'   => false,
        ];
        $output = [
            [
                'name'     => 'cms',
                'contents' => $cms,
                'filename' => 'cms.cms',
            ]
        ];
        
        $cliente = new Client( $config );
        
        $request = $cliente->request( 'POST', $this->validador_uri, ['multipart' => $output] );
        $response = \GuzzleHttp\json_decode( $request->getBody() );
        
        if ( $response->estatus === self::VALIDADOR_STATUS_ERROR ) {
            throw new Exception( $response->respuesta );
        }
    
        if ( $response->estatus === self::VALIDADOR_STATUS_SUCCESS && empty( $response->campos->url ) ) {
            throw new Exception( "La url del pdf a consultar esta vacía." );
        }
        
        return $response->campos->url;
    }
    
    /**
     * @param string $url
     * @param string $path
     * @param string $filename
     * @return bool
     * @throws \Exception
     */
    public function savePDF( string $url, string $path, string $filename )
    : bool
    {
        // agregar barra al final si falta
        if ( substr( $path, -1 ) != "/" ) {
            $path .= "/";
        }
        
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        
        $response = curl_exec( $ch );
    
        if ( curl_errno( $ch ) ) {
            throw new Exception( curl_error( $ch ) );
        }
    
        curl_close( $ch );
    
        if ( !file_put_contents( "$path$filename", $response ) ) {
            throw new Exception( "No se pudo guardar el archivo $filename" );
        }
        
        return true;
    }
    
    /**
     * @param string $path
     * @param string $hash
     * @return bool
     * @throws \SoapFault
     * @throws \Exception
     */
    public function saveXML( string $path, string $hash )
    : bool
    {
        $documentoVO = new DocumentoVO( $this->obtenerEvidenciaXmlSHA2( $hash ) );
        
        $nombre    = $documentoVO->getNombre();
        $contenido = $documentoVO->getContenido();
    
        if ( !file_put_contents( "$path$nombre", $contenido ) ) {
            throw new Exception( "No se pudo guardar el archivo $nombre" );
        }
    
        return true;
    }
    
    /**
     * @param \xIDMONx\SEI\SolicitudVO $solicitudVO
     * @return object
     * @throws \SoapFault
     */
    private function nuevaSolicitudBatch( SolicitudVO $solicitudVO )
    {
        $options = [
            'login'        => $this->user,
            'password'     => $this->password,
            'soap_version' => SOAP_1_1,
            'compression'  => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'encoding'     => 'UTF-8',
            'trace'        => true,
            'exceptions'   => true,
            'cache_wsdl'   => WSDL_CACHE_NONE
        ];
        
        $cliente = new SoapClient( $this->endpoint, $options );
        
        $serializer = new XMLSerializer();
    
        $std_class = json_decode( json_encode( ['solicitud' => $solicitudVO] ) );
        
        $xml = $serializer::generateValidXmlFromObj( $std_class, 'ns1:nuevaSolicitudBatch', 'item' );
    
        $soapParams = [new SoapVar( $xml, XSD_ANYXML )];
        
        $response = $cliente->__soapCall( 'nuevaSolicitudBatch', $soapParams );
        
        return $response->return;
    }
    
    /**
     * @param \xIDMONx\SEI\SolicitudVO $solicitudVO
     * @return object
     * @throws \SoapFault
     */
    private function nuevaSolicitudBatchSHA2( SolicitudVO $solicitudVO )
    {
        $options = [
            'login'        => $this->user,
            'password'     => $this->password,
            'soap_version' => SOAP_1_1,
            'compression'  => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'encoding'     => 'UTF-8',
            'trace'        => true,
            'exceptions'   => true,
            'cache_wsdl'   => WSDL_CACHE_NONE
        ];
    
        $cliente = new SoapClient( $this->endpoint, $options );
    
        $serializer = new XMLSerializer();
    
        $std_class = json_decode( json_encode( ['solicitud' => $solicitudVO] ) );
    
        $xml = $serializer::generateValidXmlFromObj( $std_class, 'ns1:nuevaSolicitudBatchSHA2', 'item' );
    
        $soapParams = [new SoapVar( $xml, XSD_ANYXML )];
    
        $response = $cliente->__soapCall( 'nuevaSolicitudBatchSHA2', $soapParams );
    
        return $response->return;
    }
    
    /**
     * @param string $hash
     * @return object
     * @throws \SoapFault
     */
    private function obtenerEvidenciaXmlSHA2( string $hash )
    {
        $options = [
            'login'        => $this->user,
            'password'     => $this->password,
            'soap_version' => SOAP_1_1,
            'compression'  => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'encoding'     => 'UTF-8',
            'trace'        => true,
            'exceptions'   => true,
            'cache_wsdl'   => WSDL_CACHE_NONE
        ];
        
        $cliente = new SoapClient( $this->endpoint, $options );
        $body = "<ns1:obtenerEvidenciaXmlSHA2><hashSolicitud>$hash</hashSolicitud></ns1:obtenerEvidenciaXmlSHA2>";
        $soapParams = [new SoapVar( $body, XSD_ANYXML )];
        $response = $cliente->__soapCall( 'obtenerEvidenciaXmlSHA2', $soapParams );
        
        return $response->return;
    }
    
    /**
     * @param string $hash
     * @return object
     * @throws \SoapFault
     */
    private function obtenerDocumentoFirmado( string $hash )
    {
        $options = [
            'login'        => $this->user,
            'password'     => $this->password,
            'soap_version' => SOAP_1_1,
            'compression'  => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'encoding'     => 'UTF-8',
            'trace'        => true,
            'exceptions'   => true,
            'cache_wsdl'   => WSDL_CACHE_NONE,
            'uri'          => self::URI_CEROPAPEL,
        ];
        
        $cliente = new SoapClient( $this->endpoint, $options );
        
        $body = "<ns1:obtenerDocumentoFirmado><hashSolicitud>$hash</hashSolicitud></ns1:obtenerDocumentoFirmado>";
        $args = [new SoapVar( $body, XSD_ANYXML )];
        
        $response = $cliente->__soapCall( 'obtenerDocumentoFirmado', $args );
        
        return $response->return;
    }
    
    /**
     * @param \xIDMONx\SEI\SignatureDTO $signatureDTO
     * @return int
     */
    private function gregorianDate( SignatureDTO $signatureDTO )
    : int
    {
        $day   = date( 'd', strtotime( $signatureDTO->getRequestDate() ) );
        $month = date( 'm', strtotime( $signatureDTO->getRequestDate() ) );
        $year  = date( 'Y', strtotime( $signatureDTO->getRequestDate() ) );
        
        return gregoriantojd( $month, $day, $year );
    }
}
