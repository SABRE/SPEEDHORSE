<?php
/**
 * File containing the eZIEImageToolFlipHor class.
 * 
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 1.2.0
 * @package ezie
 */
class eZIEImageToolFlipHorizontally extends eZIEImageAction
{
    /**
     * Creates a horizontal flip filter
     * @param  array(int) $region Affected region, as an array of 4 keys: w, h, x, y
     * @return array( ezcImageFilter )
     */
    static function filter( $region = null )
    {
        return array(
            new ezcImageFilter( 
                'horizontalFlip',
                array( 
                    'region' => $region 
                )
            )
        );
    }
}

?>
