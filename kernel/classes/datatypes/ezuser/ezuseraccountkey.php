<?php
//
// Definition of eZUserAccountKey class
//
// Created on: <22-Mar-2003 14:52:37 bf>
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
  \class eZUserAccountKey ezuseraccountkey.php
  \ingroup eZDatatype
  \brief The class eZUserAccountKey does

*/

class eZUserAccountKey extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZUserAccountKey( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '0..1' ),
                                         'hash_key' => array( 'name' => 'HashKey',
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         'time' => array( 'name' => 'Time',
                                                          'datatype' => 'integer',
                                                          'default' => 0,
                                                          'required' => true )
                                         ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'sort' => array( 'id' => 'asc' ),
                      'class_name' => 'eZUserAccountKey',
                      'name' => 'ezuser_accountkey' );
    }

    static function createNew( $userID, $hashKey, $time)
    {
        return new eZUserAccountKey( array( 'user_id' => $userID,
                                            'hash_key' => $hashKey,
                                            'time' => $time ) );
    }

    static function fetchByKey( $hashKey )
    {
        return eZPersistentObject::fetchObject( eZUserAccountKey::definition(),
                                                null,
                                                array( 'hash_key' => $hashKey ),
                                                true );
    }

    /*!
     Remove account keys belonging to user \a $userID
    */
    static function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZUserAccountKey::definition(),
                                          array( 'user_id' => $userID ) );
    }

}

?>
