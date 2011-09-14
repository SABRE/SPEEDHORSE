<?php
//
// Definition of eZForgotPassword class
//
// Created on: <17-���-2003 11:40:49 sp>
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
  \class eZForgotPassword ezforgotpassword.php
  \ingroup eZDatatype
  \brief The class eZForgotPassword does

*/

class eZForgotPassword extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZForgotPassword( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "user_id" => array( 'name' => "UserID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '0..*' ),
                                         "hash_key" => array( 'name' => "HashKey",
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         "time" => array( 'name' => "Time",
                                                          'datatype' => 'integer',
                                                          'default' => 0,
                                                          'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZForgotPassword",
                      "name" => "ezforgot_password" );
    }

    static function createNew( $userID, $hashKey, $time)
    {
        return new eZForgotPassword( array( "user_id" => $userID,
                                            "hash_key" => $hashKey,
                                            "time" => $time ) );
    }

    static function fetchByKey( $hashKey )
    {
        return eZPersistentObject::fetchObject( eZForgotPassword::definition(),
                                                null,
                                                array( "hash_key" => $hashKey ),
                                                true );
    }

    /*!
     \static
     Removes all password reminders in the database.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezforgot_password" );
    }

    /*!
     Remove forgot password entries belonging to user \a $userID
    */
    static function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZForgotPassword::definition(),
                                          array( 'user_id' => $userID ) );
    }
}

?>
