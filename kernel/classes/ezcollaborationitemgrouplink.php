<?php
//
// Definition of eZCollaborationItemGroupLink class
//
// Created on: <22-Jan-2003 15:51:09 amos>
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
  \class eZCollaborationItemGroupLink ezcollaborationitemgrouplink.php
  \brief The class eZCollaborationItemGroupLink does

*/

class eZCollaborationItemGroupLink extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationItemGroupLink( $row )
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
                                         'group_id' => array( 'name' => 'GroupID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true,
                                                              'foreign_class' => 'eZCollaborationGroup',
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
                                                               'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      'keys' => array( 'collaboration_id', 'group_id', 'user_id' ),
                      'function_attributes' => array( 'user' => 'user',
                                                      'collaboration_item' => 'collaborationItem',
                                                      'collaboration_group' => 'collaborationGroup' ),
                      'class_name' => 'eZCollaborationItemGroupLink',
                      'sort' => array( 'modified' => 'asc' ),
                      'name' => 'ezcollab_item_group_link' );
    }

    static function create( $collaborationID, $groupID, $userID )
    {
        $date_time = time();
        $row = array(
            'collaboration_id' => $collaborationID,
            'group_id' => $groupID,
            'is_read' => false,
            'is_active' => true,
            'last_read' => 0,
            'user_id' => $userID,
            'created' => $date_time,
            'modified' => $date_time );
        return new eZCollaborationItemGroupLink( $row );
    }

    static function addItem( $groupID, $collaborationID, $userID )
    {
        $groupLink = eZCollaborationItemGroupLink::create( $collaborationID, $groupID, $userID );

        $db = eZDB::instance();
        $db->begin();
        $groupLink->store();
        $itemStatus = eZCollaborationItemStatus::create( $collaborationID, $userID );
        $itemStatus->store();
        $db->commit();

        return $groupLink;
    }

    function fetch( $collaborationID, $groupID, $userID = false, $asObject = true )
    {
        if ( $userID == false )
            $userID == eZUser::currentUserID();
        return eZPersistentObject::fetchObject( eZCollaborationItemGroupLink::definition(),
                                                null,
                                                array( 'collaboration_id' => $collaborationID,
                                                       'group_id' => $groupID,
                                                       'user_id' => $userID ),
                                                $asObject );
    }

    function fetchList( $collaborationID, $userID = false, $asObject = true )
    {
        if ( $userID == false )
            $userID == eZUser::currentUserID();
        return eZPersistentObject::fetchObjectList( eZCollaborationItemGroupLink::definition(),
                                                    null,
                                                    array( 'collaboration_id' => $collaborationID,
                                                           'user_id' => $userID ),
                                                    null, null,
                                                    $asObject );
    }

    function user()
    {
        if ( isset( $this->UserID ) and $this->UserID )
        {
            return eZUser::fetch( $this->UserID );
        }
        return null;
    }

    function collaborationItem()
    {
        if ( isset( $this->CollaborationID ) and $this->CollaborationID )
        {
            return eZCollaborationItem::fetch( $this->CollaborationID, $this->UserID );
        }
        return null;
    }

    function collaborationGroup()
    {
        if ( isset( $this->GroupID ) and $this->GroupID )
        {
            return eZCollaborationGroup::fetch( $this->GroupID, $this->UserID );
        }
        return null;
    }


    /// \privatesection
    public $CollaborationID;
    public $GroupID;
    public $UserID;
    public $Created;
    public $Modified;
}

?>
