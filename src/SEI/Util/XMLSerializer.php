<?php

namespace xIDMONx\SEI\Util;

/**
 * Class XMLSerializer
 *
 * @author  gear_
 * SelloElectronico
 * @package xIDMONx\SEI\Util
 */
class XMLSerializer
{
    /**
     * @param \stdClass $obj
     * @param string    $node_block
     * @param string    $node_name
     * @return string
     */
    public static function generateValidXmlFromObj( \stdClass $obj, string $node_block = 'nodes', string $node_name = 'node' )
    : string
    {
        $arr = get_object_vars( $obj );
        return self::generateValidXmlFromArray( $arr, $node_block, $node_name );
    }
    
    /**
     * @param        $array
     * @param string $node_block
     * @param string $node_name
     * @return string
     */
    public static function generateValidXmlFromArray( $array, string $node_block = 'nodes', string $node_name = 'node' )
    : string
    {
        $xml = '<' . $node_block . '>';
        $xml .= self::generateXmlFromArray( $array, $node_name );
        $xml .= '</' . $node_block . '>';
        
        return $xml;
    }
    
    /**
     * @param $array
     * @param $node_name
     * @return string
     */
    private static function generateXmlFromArray( $array, $node_name )
    : string
    {
        $xml = '';
        if ( is_array( $array ) || is_object( $array ) ) {
            foreach ( $array as $key => $value ) {
                if ( is_numeric( $key ) ) {
                    $key = $node_name;
                }
                $xml .= '<' . $key . '>' . self::generateXmlFromArray( $value, $node_name ) . '</' . $key . '>';
            }
        } else {
            if ( is_null( $array ) ) {
                $xml = 'NULL';
            } else {
                $xml = htmlspecialchars( $array, ENT_QUOTES );
            }
        }
        return $xml;
    }
}
