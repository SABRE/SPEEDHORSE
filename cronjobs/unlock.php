<?php
//
// Created on: <10-Mar-09 11:48:24 jr>
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

$cli->output( 'Fetching objects with status : "locked"' );

$lockedObjectIDList = fetchLockedObjects();

if( !$lockedObjectIDList )
{
    $cli->output( 'No locked objects.' );
    $cli->output( 'Done' );
    return;
}

foreach( $lockedObjectIDList as $lockedContentObjectID )
{
    $object = eZContentObject::fetch( $lockedContentObjectID );

    $cli->output( 'Removing lock of '                                       , false );
    $cli->output( $cli->stylize( 'emphasize', $object->attribute( 'name' ) ), false );
    $cli->output( ' ... '                                                   , false );

    $status = unlockObject( $lockedContentObjectID );

    $statusString = 'Failed';
    $statusColor  = 'red';

    if( $status )
    {
        $statusString = 'Success';
        $statusColor  = 'green';
    }

    $cli->output( $cli->stylize( $statusColor, $statusString ) );
}

$cli->output( 'Done' );

function fetchLockedObjects()
{
    $db = eZDB::instance();
    $sql = "SELECT ezcobj_state_link.contentobject_id
            FROM ezcobj_state_link, ezcobj_state
            WHERE ezcobj_state_link.contentobject_state_id = ezcobj_state.id
              AND ezcobj_state.identifier = 'locked'";

    $rows = $db->arrayQuery( $sql );

    if( $rows )
    {
        $contentObjectIDList = array();
        foreach( $rows as $row )
            $contentObjectIDList[] = $row['contentobject_id'];

        return $contentObjectIDList;
    }

    return false;
}

function unlockObject( $contentObjectID )
{
    $db  = eZDB::instance();
    $sql = 'UPDATE ezcobj_state_link
            SET contentobject_state_id = 1
            WHERE contentobject_id       = '. $db->escapeString( $contentObjectID ) .'
             AND  contentobject_state_id = 2';

    return $db->query( $sql );
}
?>
