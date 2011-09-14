<?php
//
// Definition of eZGeneralDigestUserSettings class
//
// Created on: <16-May-2003 13:06:24 sp>
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
  \class eZGeneralDigestUserSettings ezgeneraldigestusersettings.php
  \brief The class eZGeneralDigestUserSettings does

*/

class eZGeneralDigestUserSettings extends eZPersistentObject
{
    const TYPE_NONE = 0;
    const TYPE_WEEKLY = 1;
    const TYPE_MONTHLY = 2;
    const TYPE_DAILY = 3;

    /*!
     Constructor
    */
    function eZGeneralDigestUserSettings( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "address" => array( 'name' => "Address",
                                                             'datatype' => 'string',
                                                             'default' => '',
                                                             'required' => true ),
                                         "receive_digest" => array( 'name' => "ReceiveDigest",
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                         "digest_type" => array( 'name' => "DigestType",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "day" => array( 'name' => "Day",
                                                         'datatype' => 'string',
                                                         'default' => '',
                                                         'required' => true ),
                                         "time" => array( 'name' => "Time",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZGeneralDigestUserSettings",
                      "name" => "ezgeneral_digest_user_settings" );
    }


    static function create( $address, $receiveDigest = 0, $digestType = self::TYPE_NONE, $day = '', $time = '' )
    {
        return new eZGeneralDigestUserSettings( array( 'address' => $address,
                                                       'receive_digest' => $receiveDigest,
                                                       'digest_type' => $digestType,
                                                       'day' => $day,
                                                       'time' => $time ) );
    }

    static function fetchForUser( $address, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZGeneralDigestUserSettings::definition(),
                                                null,
                                                array( 'address' => $address ),
                                                $asObject );
    }

    static function removeByAddress( $address )
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezgeneral_digest_user_settings WHERE address='" . $db->escapeString( $address ) . "'" );
    }

    /*!
     \static
     Removes all general digest settings for all users.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezgeneral_digest_user_settings" );
    }
}

?>
