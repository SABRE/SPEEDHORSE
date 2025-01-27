<?php
//
// Definition of eZCollaborationFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
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
  \class eZCollaborationFunctionCollection ezcontentfunctioncollection.php
  \brief The class eZCollaborationFunctionCollection does

*/

class eZCollaborationFunctionCollection
{
    /*!
     Constructor
    */
    function eZCollaborationFunctionCollection()
    {
    }

    function fetchParticipant( $itemID, $participantID )
    {
        if ( $participantID === false )
        {
            $user = eZUser::currentUser();
            $participantID = $user->attribute( 'contentobject_id' );
        }
        $participant = eZCollaborationItemParticipantLink::fetch( $itemID, $participantID );
        if ( $participant === null )
        {
            $resultArray = array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $resultArray = array( 'result' => $participant );
        }
        return $resultArray;
    }

    function fetchParticipantList( $itemID, $sortBy, $offset, $limit )
    {
        $itemParameters = array( 'item_id' => $itemID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy );
        $children = eZCollaborationItemParticipantLink::fetchParticipantList( $itemParameters );
        if ( $children === null )
        {
            $resultArray = array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $resultArray = array( 'result' => $children );
        }
        return $resultArray;
    }

    function fetchParticipantMap( $itemID, $sortBy, $offset, $limit, $field )
    {
        $itemParameters = array( 'item_id' => $itemID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy );
        if ( $field !== false )
            $itemParameters['sort_field'] = $field;
        $children = eZCollaborationItemParticipantLink::fetchParticipantMap( $itemParameters );
        if ( $children === null )
        {
            $resultArray = array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $resultArray = array( 'result' => $children );
        }
        return $resultArray;
    }

    function fetchMessageList( $itemID, $sortBy, $offset, $limit )
    {
        $itemParameters = array( 'item_id' => $itemID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy );
        $children = eZCollaborationItemMessageLink::fetchItemList( $itemParameters );
        if ( $children === null )
        {
            $resultArray = array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $resultArray = array( 'result' => $children );
        }
        return $resultArray;
    }

    function fetchItemList( $sortBy, $offset, $limit, $status, $isRead, $isActive, $parentGroupID )
    {
        $itemParameters = array( 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy,
                                 'is_read' => $isRead,
                                 'is_active' => $isActive,
                                 'parent_group_id' => $parentGroupID );
        if ( $status !== false )
            $itemParameters['status'] = $status;
        $children = eZCollaborationItem::fetchList( $itemParameters );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $children );
    }

    function fetchItemCount( $isRead, $isActive, $parentGroupID, $status )
    {
        $itemParameters = array( 'is_read' => $isRead,
                                 'is_active' => $isActive,
                                 'parent_group_id' => $parentGroupID
                                 );
        if ( $status !== false )
            $itemParameters['status'] = $status;
        $count = eZCollaborationItem::fetchListCount( $itemParameters );
        return array( 'result' => $count );
    }

    function fetchGroupTree( $parentGroupID, $sortBy, $offset, $limit, $depth )
    {
        $treeParameters = array( 'parent_group_id' => $parentGroupID,
                                 'offset' => $offset,
                                 'limit' => $limit,
                                 'sort_by' => $sortBy,
                                 'depth' => $depth );
        $children = eZCollaborationGroup::subTree( $treeParameters );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $children );
    }

    function fetchObjectTreeCount( $parentNodeID, $class_filter_type, $class_filter_array, $depth )
    {
        $node = eZContentObjectTreeNode::fetch( $parentNodeID );
        $childrenCount = $node->subTreeCount( array( 'Limitation' => null,
                                                     'ClassFilterType' => $class_filter_type,
                                                     'ClassFilterArray' => $class_filter_array,
                                                     'Depth' => $depth ) );
        if ( $childrenCount === null )
        {
            $resultArray = array( 'error' => array( 'error_type' => 'kernel',
                                                    'error_code' => eZError::KERNEL_NOT_FOUND ) );
        }
        else
        {
            $resultArray = array( 'result' => $childrenCount );
        }
        return $resultArray;
    }

}

?>
