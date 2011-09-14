<?php
//
// Definition of eZStringUtils class
//
// Created on: <29-Sep-2006 21:35:51 sp>
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
  \class eZStringUtils ezstringutils.php
  \brief The class eZStringUtils does

*/

class eZStringUtils
{
    /*!
     Constructor
    */
    function eZStringUtils()
    {
    }

    static function  explodeStr( $str, $delimiter = '|' )
    {
        $offset = 0;
        $array = array();

        while( ( $pos = strpos( $str, $delimiter, $offset )  ) !== false )
        {
            $strPart = substr( $str, 0, $pos );
            if ( preg_match( '/(\\\\+)$/', $strPart, $matches ) )
            {
                if ( strlen( $matches[0] ) % 2 !== 0 )
                {
                    $offset = $pos+1;
                    continue;
                }
            }
            $array[] = str_replace( '\\\\', '\\', str_replace("\\$delimiter", $delimiter, $strPart ) );
            $str = substr( $str, $pos + 1 );
            $offset = 0;

        }
        $array[] = str_replace( '\\\\', '\\', str_replace("\\$delimiter", $delimiter, $str ) );
        return $array;
    }

    static function implodeStr( $values, $delimiter = '|' )
    {
        $str = '';
        while ( list( $key, $value ) = each( $values ) )
        {
            $values[$key] = str_replace( $delimiter, "\\$delimiter", str_replace( '\\', '\\\\', $value ) );
        }
        return implode( $delimiter, $values );
    }


}

?>
