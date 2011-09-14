<?php
//
// Definition of eZNetCrypt class
//
// Created on: <06-Jul-2005 14:07:28 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Network
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
//     Att: eZ Systems AS Licensing Dept., Klostergata 30, N-3732 Skien, Norway
//
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file eznetcrypt.php
*/

/**
 * @deprecated Class is no longer doing anything as of php5
 */
class eZNetCrypt
{

    /**
     * Encrypt text using DES if encryption is enabled (it's not)
     *
     * @param string $text
     * @return string
    */
    public static function encrypt( $text )
    {
        if ( self::$enabled === false )
        {
            return $text;
        }

        return mcrypt_encrypt( MCRYPT_3DES, self::$key, $text, MCRYPT_MODE_CFB, self::$IV );
    }

    /**
     * Decrypt text using DES if encryption is enabled (it's not)
     *
     * @param string $text
     * @return string
    */
    public static function decrypt( $enc )
    {
        if ( self::$enabled === false )
        {
            return $enc;
        }

        return mcrypt_decrypt( MCRYPT_3DES, self::$key, $enc, MCRYPT_MODE_CFB, self::$IV );
    }

    /**
     * Generate IV values
    */
    private function setIV()
    {
//        self::$IVSize = mcrypt_get_iv_size( MCRYPT_XTEA, MCRYPT_MODE_ECB );
//        self::$IV = mcrypt_create_iv( self::$IVSize, MCRYPT_RAND );
    }

    private static $IVSize = null;
    private static $IV = null;
    private static $key = '*321 regninger 123*';

    private static $enabled = false;
}

?>
