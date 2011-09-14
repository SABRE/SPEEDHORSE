<?php
//
// Definition of eZBCMath class
//
// Created on: <04-Nov-2005 12:26:52 dl>
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
  \class eZBCMath ezbcmath.php
  \brief Handles calculation using bcmath library.
*/

class eZBCMath extends eZPHPMath
{
    const DEFAULT_SCALE = 10;

    function eZBCMath( $params = array () )
    {
        if( isset( $params['scale'] ) && is_numeric( $params['scale'] ) )
            $this->setScale( $params['scale'] );
        else
            $this->setScale( self::DEFAULT_SCALE );
    }

    function scale()
    {
        return $this->Scale;
    }

    function setScale( $scale )
    {
        $this->Scale = $scale;
    }

    function add( $a, $b )
    {
        return ( bcadd( $a, $b, $this->Scale ) );
    }

    function sub( $a, $b )
    {
        return ( bcsub( $a, $b, $this->Scale ) );
    }

    function mul( $a, $b )
    {
        return ( bcmul( $a, $b, $this->Scale ) );
    }

    function div( $a, $b )
    {
        return ( bcdiv( $a, $b, $this->Scale ) );
    }

    function pow( $base, $exp )
    {
        return ( bcpow( $base, $exp, $this->Scale ) );
    }

    function ceil( $value, $precision, $target )
    {
        $result = eZPHPMath::ceil( $value, $precision, $target );
        $result = rtrim( $result, '0' );
        $result = rtrim( $result, '.' );
        return $result;
    }

    function floor( $value, $precision, $target )
    {
        $result = eZPHPMath::floor( $value, $precision, $target );
        $result = rtrim( $result, '0' );
        $result = rtrim( $result, '.' );
        return $result;
    }

    function round( $value, $precision, $target )
    {
        $result = $value;
        $fractPart = $this->fractval( $value, $precision + 1 );
        if ( strlen( $fractPart ) > $precision )
        {
            $lastDigit = (int)substr( $fractPart, -1, 1 );
            $fractPart = substr( $fractPart, 0, $precision );
            if ( $lastDigit >= 5 )
                $fractPart = $this->add( $fractPart, 1 );

            $fractPart = $this->div( $fractPart, $this->pow( 10, $precision ) );

            $result = $this->add( $this->intval( $value ), $fractPart );
            $result = $this->adjustFractPart( $result, $precision, $target );

            $result = rtrim( $result, '0' );
            $result = rtrim( $result, '.' );
        }

        return $result;
    }


    /// \privatesection
    public $Scale;
}

?>
