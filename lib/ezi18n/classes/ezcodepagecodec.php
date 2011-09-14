<?php
//
// Definition of eZCodePageCodec class
//
// Created on: <07-Mar-2002 10:19:23 amos>
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

/*!
  \class eZCodePageCodec ezcodepagecodec.php
  \ingroup eZI18N
  \brief The class eZCodePageCodec does

*/

class eZCodePageCodec extends eZTextCodec
{
    /*!
     Initializes the codepage codec with $name
    */
    function eZCodePageCodec( $name )
    {
        $this->eZTextCodec( $name );
        $this->CodePage = false;
    }

    function toUnicode( $str )
    {
        $ustr = "";
        if ( !is_string( $str ) or
             !$this->isValid() )
            return $ustr;
        $len = strlen( $str );
        for ( $i = 0; $i < $len; ++$i )
        {
//             $char_code = $this->CodePage->
//             $ustr .= $this->toUtf8( $char_code );
            $char = $str[$i];
            $ustr .= $this->CodePage->charToUtf8( $char );
        }
        return $ustr;
    }

    function fromUnicode( $str )
    {
        $ustr = "";
        if ( !is_string( $str ) or
             !$this->isValid() )
            return $ustr;
        $utf8_codec = eZUTF8Codec::instance();
        $len = strlen( $str );
        for ( $i = 0; $i < $len; )
        {
            $char_code = $utf8_codec->fromUtf8( $str, $i, $l );
            if ( $char_code !== false )
            {
                $i += $l;
                $ustr .= chr( $this->CodePage->unicodeToChar( $char_code ) );
            }
            else
                ++$i;
        }
        return $ustr;
    }

    /*!
     Returns true if a codepage has been set.
    */
    function isValid()
    {
        return $this->CodePage instanceof eZCodePage;
    }

    /*!
     Returns the codepage.
    */
    function codePage()
    {
        return $this->CodePage;
    }

    /*!
     Sets the current codepage which is used for utf8/text translation.
    */
    function setCodePage( $cp )
    {
        $this->CodePage = $cp;
    }

    public $CodePage;
}

?>
