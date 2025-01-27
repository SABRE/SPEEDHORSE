<?php
/**
 * File containing the eZIEImageFilterSepia class.
 * 
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 1.2.0
 * @package ezie
 */
class eZIEImageFilterSepia extends eZIEImageAction
{
    /**
     * Creates a sepia filter
     * 
     * @param array $ (int) $region Affected region, as a 4 keys array: x, y, w, h
     * @return array ( ezcImageFilter )
     */
    static function filter( $region = null )
    {
        return array(
            new ezcImageFilter( 
                'colorspace',
                array( 
                    'space' => ezcImageColorspaceFilters::COLORSPACE_SEPIA,
                    'region' => $region 
                ) 
            ) 
        );
    }
}

?>
