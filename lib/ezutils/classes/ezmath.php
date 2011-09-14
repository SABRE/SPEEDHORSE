<?php
//
// Created on: <16-Mar-2003 17:56:32 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.5.0
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
// SOFTWARE LICENSE: eZ Proprietary Use License v1.0
// NOTICE: >
//   This source file is part of the eZ Publish (tm) CMS and is
//   licensed under the terms and conditions of the eZ Proprietary
//   Use License v1.0 (eZPUL).
// 
//   A copy of the eZPUL was included with the software. If the
//   license is missing, request a copy of the license via email
//   at eZPUL-v1.0@ez.no or via postal mail at
//     Attn: Licensing Dept. eZ Systems AS, Klostergata 30, N-3732 Skien, Norway
// 
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file
*/

/*!
  \defgroup eZMath eZ Math library
*/

/*!
  \class eZMath ezmath.php
  \brief eZMath provide a simple math library for common math operations
*/

class eZMath
{
    /*!
     Constructor
    */
    function eZMath()
    {
    }

    /*!
     \static

     Normalize RGB color array to 0..1 range

     \param array to normalize

     \return normalized array
    */
    static function normalizeColorArray( $array )
    {
        foreach ( array_keys( $array ) as $key )
        {
            $array[$key] = (float)$array[$key]/256;
        }

        return $array;
    }

    /*!
     \static

     Convert RGB to CMYK, Normalized values, between 0 and 1

     \param rgbArray RGB array
     \return CMYK array
    */
    static function rgbToCMYK( $rgbArray )
    {
        $cya = 1 - min( 1, max( (float)$rgbArray['r'], 0 ) );
        $mag = 1 - min( 1, max( (float)$rgbArray['g'], 0 ) );
        $yel = 1 - min( 1, max( (float)$rgbArray['b'], 0 ) );

        $min = min( $cya, $mag, $yel );
        if ( 1 - $min == 0 )
        {
            return array( 'c' => 1,
                          'm' => 1,
                          'y' => 1,
                          'k' => 0 );
        }

        return array( 'c' => ( $cya - $min ) / ( 1 - $min ),
                      'm' => ( $mag - $min ) / ( 1 - $min ),
                      'y' => ( $yel - $min ) / ( 1 - $min ),
                      'k' => $min );
    }

    /*!
     \static

     Convert rgb to CMYK

     \param r R
     \param g G
     \param b B

     \return CMYK return array
    */
    static function rgbToCMYK2( $r, $g, $b )
    {
        return eZMath::rgbToCMYK( array( 'r' => $r,
                                         'g' => $g,
                                         'b' => $b ) );
    }
}
?>
