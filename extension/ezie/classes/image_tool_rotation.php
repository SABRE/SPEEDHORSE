<?php
/**
* File containing the eZIEImageToolRotation class.
* 
* @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
* @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
* @version 1.2.0
* @package ezie
*/
class eZIEImageToolRotation extends eZIEImageAction
{
    /**
    * Returns a rotation filter
    * 
    * @param  int $angle Rotation angle. Valid range [0-360]
    * @param  string $backgroundColor Background color hex code
    * @return array( ezcImageFilter  )
    */
    static function filter( $angle, $backgroundColor = 'FFFFFF' )
    {
        return array(
            new ezcImageFilter( 
                'rotate',
                array( 
                    'angle'      => $angle,
                    'background' => $backgroundColor 
                )
            )
        );
    }
}

?>
