<?php

namespace xIDMONx\SEI;

/**
 * Class DocumentoVO
 *
 * @author  xIDMONx
 * @package xIDMONx\SEI
 */
class DocumentoVO
{
    /**
     * @var string
     */
    public $contenido;
    
    /**
     * @var string
     */
    public $hash;
    
    /**
     * @var string
     */
    public $nombre;
    
    /**
     * @param null $return
     */
    public function __construct( $return = null )
    {
        if ( !empty( $return ) ) {
            $this->nombre    = $return->nombre;
            $this->contenido = $return->contenido;
        }
    }
    
    /**
     * @return string
     */
    public function getContenido()
    : string
    {
        return $this->contenido;
    }
    
    /**
     * @param string $contenido
     */
    public function setContenido( string $contenido )
    : void
    {
        $this->contenido = $contenido;
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
     * @return string
     */
    public function getNombre()
    : string
    {
        return $this->nombre;
    }
    
    /**
     * @param string $nombre
     */
    public function setNombre( string $nombre )
    : void
    {
        $this->nombre = $nombre;
    }
}
