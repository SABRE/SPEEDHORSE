<?php
//
// Definition of eZCollaborationItemStatus class
//
// Created on: <30-Jan-2003 13:51:22 amos>
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
  \class eZCollaborationItemStatus ezcollaborationitemstatus.php
  \brief The class eZCollaborationItemStatus does

*/

class eZCollaborationItemStatus extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationItemStatus( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'collaboration_id' => array( 'name' => 'CollaborationID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZCollaborationItem',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '1..*' ),
                                         'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '1..*' ),
                                         'is_read' => array( 'name' => 'IsRead',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'is_active' => array( 'name' => 'IsActive',
                                                               'datatype' => 'integer',
                                                               'default' => 1,
                                                               'required' => true ),
                                         'last_read' => array( 'name' => 'LastRead',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      'keys' => array( 'collaboration_id', 'user_id' ),
                      'class_name' => 'eZCollaborationItemStatus',
                      'name' => 'ezcollab_item_status' );
    }

    static function create( $collaborationID, $userID = false )
    {
        if ( $userID === false )
            $userID = eZUser::currentUserID();
        $row = array(
            'collaboration_id' => $collaborationID,
            'user_id' => $userID,
            'is_read' => false,
            'is_active' => true,
            'last_read' => 0 );
        return $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID] = new eZCollaborationItemStatus( $row );
    }

    function store( $fieldFilters = null )
    {
        $stored = eZPersistentObject::store( $fieldFilters );
        $this->updateCache();
        return $stored;
    }

    function updateCache()
    {
        $userID = $this->UserID;
        $collaborationID = $this->CollaborationID;
        $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID] = $this;
    }

    static function fetch( $collaborationID, $userID = false, $asObject = true )
    {
        if ( $userID === false )
        {
            $userID = eZUser::currentUserID();
        }
        if ( !isset( $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID] ) )
        {
            $conditions = array( 'collaboration_id' => $collaborationID,
                                 'user_id' => $userID );
            $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID] = eZPersistentObject::fetchObject(
                eZCollaborationItemStatus::definition(),
                null,
                $conditions,
                $asObject );
        }
        return $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID];
    }

    static function setLastRead( $collaborationID, $userID = false, $timestamp = false )
    {
        if ( $timestamp === false )
            $timestamp = time();

        eZCollaborationItemStatus::updateFields( $collaborationID, $userID, array( 'last_read' => $timestamp,
                                                                                   'is_read' => 1 ) );
    }

    static function updateFields( $collaborationID, $userID = false, $fields )
    {
        if ( $userID === false )
            $userID = eZUser::currentUserID();

        eZPersistentObject::updateObjectList( array( 'definition' => eZCollaborationItemStatus::definition(),
                                                     'update_fields' => $fields,
                                                     'conditions' => array( 'collaboration_id' => $collaborationID,
                                                                            'user_id' => $userID ) ) );
        $statusObject =& $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID];
        if ( isset( $statusObject ) )
        {
            foreach ( $fields as $field => $value )
            {
                $statusObject->setAttribute( $field, $value );
            }
        }
    }

}

?>
