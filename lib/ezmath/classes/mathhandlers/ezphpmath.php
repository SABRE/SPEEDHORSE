<?php
//
// Definition of eZPHPMath class
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
  \class eZPHPMath ezphpmath.php
  \brief Handles calculation using standard php's functions.
*/

class eZPHPMath
{
    function eZPHPMath( $params = array() )
    {
    }

    static function create( $type, $params = array() )
    {
        $filename = 'lib/ezmath/classes/mathhandlers/' . $type . '.php';

        if ( file_exists( $filename ) )
        {
            include_once( $filename );
            return new $type( $params );
        }

        return false;
    }

    function add( $a, $b )
    {
        return ( (float)$a + (float)$b );
    }

    function sub( $a, $b )
    {
        return ( (float)$a - (float)$b );
    }

    function mul( $a, $b )
    {
        $c = ( (float)$a * (float)$b );
        return ( (float)$a * (float)$b );
    }

    function div( $a, $b )
    {
        return ( (float)$a / (float)$b );
    }

    function pow( $base, $exp )
    {
        return ( pow( $base, $exp ) );
    }

    function round( $value, $precision, $target )
    {
        $result = round( $value, $precision );
        $result = $this->adjustFractPart( $result, $precision, $target );
        return $result;
    }

    function ceil( $value, $precision, $target )
    {
        $fractStr = $this->fractval( $value );
        $fractPart = (int)substr( $fractStr, 0, $precision );

        $fractLen = strlen( $fractStr );
        // actual ceiling
        if ( $fractLen > $precision )
            $fractPart += 1;

        // adjust precision
        if ( $fractLen < $precision )
            $precision = $fractLen;

        // create resulting value
        $fractPart = $this->div( $fractPart, $this->pow( 10, $precision ) );

        $result = $this->add( $this->intval( $value ), $fractPart );
        $result = $this->adjustFractPart( $result, $precision, $target );

        return $result;
    }

    function floor( $value, $precision, $target )
    {
        $fractPart = $this->fractval( $value, $precision );

        // adjust precision
        $fractLen = strlen( $fractPart );
        if ( $fractLen < $precision )
            $precision = $fractLen;

        $fractPart = $this->div( $fractPart, $this->pow( 10, $precision ) );

        $result = $this->add( $this->intval( $value ), $fractPart );
        $result = $this->adjustFractPart( $result, $precision, $target );

        return $result;
    }


    function adjustFractPart( $number, $precision, $target )
    {
        if ( is_numeric( $target ) )
        {
            $target = substr( $target, 0, $precision );
            $targetPrecision = strlen( $target );
            $target = $this->div( $target, $this->pow( 10, $precision ) );

            $intPart = $this->intval( $number );
            $fractPart = $this->fractval( $number, $precision - $targetPrecision );
            $fractPart = $this->div( $fractPart, $this->pow( 10, $this->sub( $precision, $targetPrecision ) ) );
            $fractPart = $this->add( $fractPart, $target );

            $number = $this->add( $intPart, $fractPart );
        }

        return $number;
    }

    function intval( $number )
    {
        $intPart = 0;
        $pos = strpos( $number, '.' );
        if ( $pos !== false )
            $intPart = substr( $number, 0, $pos );
        else
            $intPart = $number;

        return $intPart;
    }

    function fractval( $number, $precision = false )
    {
        $fractPart = 0;
        $pos = strpos( $number, '.' );
        if ( $pos !== false )
        {
            if ( $precision === false )
                $fractPart = substr( $number, $pos + 1 );
            else
                $fractPart = substr( $number, $pos + 1, $precision );
        }

        return $fractPart;
    }
}

?>
