<?php
/**
 * File containing the eZIEImageToolCrop class.
 * 
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 1.2.0
 * @package ezie
 */
class eZIEImageToolCrop extends eZIEImageAction
{
    /**
    * Creates a crop filter
    * 
    * @param  array(int) $region Affected region, as a 4 keys array: w, h, x, y
    * @return array( ezcImageFilter )
    */
    public static function filter( $region )
    {
        $r = array(
            'x'      => intval( $region['x'] ),
            'y'      => intval( $region['y'] ),
            'width'  => intval( $region['w'] ),
            'height' => intval( $region['h'] ) 
        );
        return array( new ezcImageFilter( 'crop', $r ) );
    }
}

?>
