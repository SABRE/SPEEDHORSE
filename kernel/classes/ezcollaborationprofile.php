<?php
//
// Definition of eZCollaborationProfile class
//
// Created on: <28-Jan-2003 16:45:06 amos>
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
  \class eZCollaborationProfile ezcollaborationprofile.php
  \brief The class eZCollaborationProfile does

*/

class eZCollaborationProfile extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationProfile( $row )
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
                                                             'multiplicity' => '1..*' ),
                                         'main_group' => array( 'name' => 'MainGroup',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true,
                                                                'foreign_class' => 'eZCollaborationGroup',
                                                                'foreign_attribute' => 'id',
                                                                'multiplicity' => '1..*' ),
                                         'data_text1' => array( 'name' => 'DataText1',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZCollaborationProfile',
                      'name' => 'ezcollab_profile' );
    }

    static function create( $userID, $mainGroup = 0 )
    {
        $date_time = time();
        $row = array( 'id' => null,
                      'user_id' => $userID,
                      'main_group' => $mainGroup,
                      'created' => $date_time,
                      'modified' => $date_time );
        $newCollaborationProfile = new eZCollaborationProfile( $row );
        return $newCollaborationProfile;
    }

    static function fetch( $id, $asObject = true )
    {
        $conditions = array( "id" => $id );
        return eZPersistentObject::fetchObject( eZCollaborationProfile::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    static function fetchByUser( $userID, $asObject = true )
    {
        $conditions = array( "user_id" => $userID );
        return eZPersistentObject::fetchObject( eZCollaborationProfile::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    /**
     * Returns a shared instance of the eZCollaborationProfile class
     * pr user id.
     * note: Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int|false $userID Uses current user id if false.
     * @return eZCollaborationProfile
     */
    static function instance( $userID = false )
    {
        if ( $userID === false )
        {
            $user = eZUser::currentUser();
            $userID = $user->attribute( 'contentobject_id' );
        }
        $instance =& $GLOBALS["eZCollaborationProfile-$userID"];
        if ( !isset( $instance ) )
        {
            $instance = eZCollaborationProfile::fetchByUser( $userID );
            if ( $instance === null )
            {
                $group = eZCollaborationGroup::instantiate( $userID, ezpI18n::tr( 'kernel/classes', 'Inbox' ) );
                $instance = eZCollaborationProfile::create( $userID, $group->attribute( 'id' ) );
                $instance->store();
            }
        }
        return $instance;
    }

}

?>
