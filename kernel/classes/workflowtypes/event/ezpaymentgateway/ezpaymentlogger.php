<?php
//
// Definition of eZPaymentLogger class
//
// Created on: <11-Jun-2004 14:18:58 dl>
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
  \class eZPaymentLogger
*/

class eZPaymentLogger
{
    function eZPaymentLogger( $fileName, $mode )
    {
        $this->file = fopen( $fileName, $mode );
    }

    static function CreateNew($fileName)
    {
        return new eZPaymentLogger( $fileName, "wt" );
    }

    static function CreateForAdd($fileName)
    {
        return new eZPaymentLogger( $fileName, "a+t" );
    }

    function writeString( $string, $label='' )
    {
        if( $this->file )
        {
            if ( is_object( $string ) || is_array( $string ) )
                $string = eZDebug::dumpVariable( $string );

            if( $label == '' )
                fputs( $this->file, $string."\r\n" );
            else
                fputs( $this->file, $label . ': ' . $string."\r\n" );
        }
    }

    function writeTimedString( $string, $label='' )
    {
        if( $this->file )
        {
            $time = $this->getTime();

            if ( is_object( $string ) || is_array( $string ) )
                $string = eZDebug::dumpVariable( $string );

            if( $label == '' )
                fputs( $this->file, $time. '  '. $string. "\n" );
            else
                fputs( $this->file, $time. '  '. $label. ': '. $string. "\n" );
        }
    }

    static function getTime()
    {
        $time = strftime( "%d-%m-%Y %H-%M" );
        return $time;
    }

    public $file;
}
?>
