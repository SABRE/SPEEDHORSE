<?php
//
// Created on: <18-Mar-2004 17:12:43 dr>
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

if ( !$isQuiet )
{
    $cli->output( "Starting processing pending search engine modifications" );
}

$contentObjects = array();
$db = eZDB::instance();

$offset = 0;
$limit = 50;

while( true )
{
    $entries = $db->arrayQuery( "SELECT DISTINCT param FROM ezpending_actions WHERE action = 'index_object'",
                                array( 'limit' => $limit,
                                       'offset' => $offset ) );

    if ( is_array( $entries ) && count( $entries ) != 0 )
    {
        foreach ( $entries as $entry )
        {
            $objectID = (int)$entry['param'];

            $cli->output( "\tIndexing object ID #$objectID" );
            $db->begin();
            $object = eZContentObject::fetch( $objectID );
            if ( $object )
            {
                eZSearch::removeObject( $object );
                eZSearch::addObject( $object );
            }
            $db->query( "DELETE FROM ezpending_actions WHERE action = 'index_object' AND param = '$objectID'" );
            $db->commit();
        }
    }
    else
    {
        break; // No valid result from ezpending_actions
    }
}

if ( !$isQuiet )
{
    $cli->output( "Done" );
}

?>
