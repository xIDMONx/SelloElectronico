<?php

namespace xIDMONx\SEI;

use ArrayObject;

/**
 * Class SolicitudVO
 *
 * @author  xIDMONx
 * @package xIDMONx\SEI
 */
class SolicitudVO
{
    /**
     * @var string
     */
    public $asunto;
    
    /**
     * @var DocumentoVO
     */
    public $documento;
    
    /**
     * @var string
     */
    public $fechaSolicitud;
    
    /**
     * @var string
     */
    public $fechaVencimiento;
    
    /**
     * @var ArrayObject
     */
    public $firmantes;
    
    /**
     * @var string
     */
    public $hash;
    
    /**
     * @var array
     */
    public $listaFirmantes;
    
    /**
     * @var boolean
     */
    public $listaOrdenada;
    
    /**
     * @var string
     */
    public $sistemaSolicitante;
    
    /**
     * @var string
     */
    public $solicitante;
    
    /**
     * @var int
     */
    public $tipoDocumento;
    
    /**
     * @return string
     */
    public function getAsunto()
    : string
    {
        return $this->asunto;
    }
    
    /**
     * @param string $asunto
     */
    public function setAsunto( string $asunto )
    : void
    {
        $this->asunto = $asunto;
    }
    
    /**
     * @return DocumentoVO
     */
    public function getDocumento()
    : DocumentoVO
    {
        return $this->documento;
    }
    
    /**
     * @param DocumentoVO $documento
     */
    public function setDocumento( DocumentoVO $documento )
    : void
    {
        $this->documento = $documento;
    }
    
    /**
     * @return string
     */
    public function getFechaSolicitud()
    : string
    {
        return $this->fechaSolicitud;
    }
    
    /**
     * @param string $fechaSolicitud
     */
    public function setFechaSolicitud( string $fechaSolicitud )
    : void
    {
        $this->fechaSolicitud = $fechaSolicitud;
    }
    
    /**
     * @return string
     */
    public function getFechaVencimiento()
    : string
    {
        return $this->fechaVencimiento;
    }
    
    /**
     * @param string $fechaVencimiento
     */
    public function setFechaVencimiento( string $fechaVencimiento )
    : void
    {
        $this->fechaVencimiento = $fechaVencimiento;
    }
    
    /**
     * @return \ArrayObject
     */
    public function getFirmantes()
    : \ArrayObject
    {
        if ( $this->firmantes == null ) {
            $this->firmantes = new \ArrayObject();
        }
        return $this->firmantes;
    }
    
    /**
     * @param \ArrayObject $firmantes
     */
    public function setFirmantes( \ArrayObject $firmantes )
    : void
    {
        $this->firmantes = $firmantes;
    }
    
    /**
     * @return string
     */
    public function getHash()
    : string
    {
        return $this->hash;
    }
    
    /**
     * @param string $hash
     */
    public function setHash( string $hash )
    : void
    {
        $this->hash = $hash;
    }
    
    /**
     * @return array
     */
    public function getListaFirmantes()
    : array
    {
        return $this->listaFirmantes;
    }
    
    /**
     * @param array $listaFirmantes
     */
    public function setListaFirmantes( array $listaFirmantes )
    : void
    {
        $this->listaFirmantes = $listaFirmantes;
    }
    
    /**
     * @return bool
     */
    public function isListaOrdenada()
    : bool
    {
        return $this->listaOrdenada;
    }
    
    /**
     * @param bool $listaOrdenada
     */
    public function setListaOrdenada( bool $listaOrdenada )
    : void
    {
        $this->listaOrdenada = $listaOrdenada;
    }
    
    /**
     * @return string
     */
    public function getSistemaSolicitante()
    : string
    {
        return $this->sistemaSolicitante;
    }
    
    /**
     * @param string $sistemaSolicitante
     */
    public function setSistemaSolicitante( string $sistemaSolicitante )
    : void
    {
        $this->sistemaSolicitante = $sistemaSolicitante;
    }
    
    /**
     * @return string
     */
    public function getSolicitante()
    : string
    {
        return $this->solicitante;
    }
    
    /**
     * @param string $solicitante
     */
    public function setSolicitante( string $solicitante )
    : void
    {
        $this->solicitante = $solicitante;
    }
    
    /**
     * @return int
     */
    public function getTipoDocumento()
    : int
    {
        return $this->tipoDocumento;
    }
    
    /**
     * @param int $tipoDocumento
     */
    public function setTipoDocumento( int $tipoDocumento )
    : void
    {
        $this->tipoDocumento = $tipoDocumento;
    }
}
