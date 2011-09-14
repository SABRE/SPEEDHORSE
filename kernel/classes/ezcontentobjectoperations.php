<?php
//
// Definition of eZContentObjectOperations class
//
// Created on: <23-Jan-2006 14:38:57 vs>
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
  \class eZContentObjectOperations ezcontentobjectoperations.php
  \brief The class eZContentObjectOperations is a place where
         content object operations are encapsulated.
  We move them out from eZContentObject because they may content code
  which is not directly related to content objects (e.g. clearing caches, etc).
*/

class eZContentObjectOperations
{
    /*!
     Removes content object and all data associated with it.
     \static
    */
    static function remove( $objectID, $removeSubtrees = true )
    {
        eZContentCacheManager::clearContentCacheIfNeeded( $objectID );

        $object = eZContentObject::fetch( $objectID );
        if ( !is_object( $object ) )
            return false;

        // TODO: Is content cache cleared for all objects in subtree ??

        if ( $removeSubtrees )
        {
            $assignedNodes = $object->attribute( 'assigned_nodes' );
            if ( count( $assignedNodes ) == 0 )
            {
                $object->purge();
                eZContentObject::expireAllViewCache();
                return true;
            }
            $assignedNodeIDArray = array();
            foreach( $assignedNodes as $node )
            {
                $assignedNodeIDArray[] = $node->attribute( 'node_id' );
            }
            eZContentObjectTreeNode::removeSubtrees( $assignedNodeIDArray, false );
        }
        else
            $object->purge();

        return true;
    }
}


?>
