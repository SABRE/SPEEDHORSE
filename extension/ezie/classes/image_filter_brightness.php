<?php
/**
 * File containing the eZIEImageFilterBrightness class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 1.2.0
 * @package ezie
 */
class eZIEImageFilterBrightness extends eZIEImageAction
{
    /**
     * Creates a brightness filter
     *
     * @param int $value Brightness value
     *
     * @return array( ezcImageFilter )
     */
    static function filter( $value = 0, $region = null )
    {
        return array(
            new ezcImageFilter(
                'brightness',
                array(
                    'value' => $value,
                    'region' => $region
                )
            )
        );
    }
}
?>
