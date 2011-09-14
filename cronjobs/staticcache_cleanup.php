<?php
//
// Created on: <28-May-2007 17:44:41 ar>
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
    $cli->output( "Starting processing pending static cache cleanups" );

$db = eZDB::instance();

$offset = 0;
$limit = 20;

do
{
    $deleteParams = array();
    $markInvalidParams = array();
    $fileContentCache = array();

    $rows = $db->arrayQuery( "SELECT DISTINCT param FROM ezpending_actions WHERE action = 'static_store'",
                                array( 'limit' => $limit,
                                       'offset' => $offset ) );
    if ( !$rows || ( empty( $rows ) ) )
        break;

    foreach ( $rows as $row )
    {
        $param = $row['param'];
        $paramList = explode( ',', $param );
        $source = $paramList[1];
        $destination = $paramList[0];
        $invalid = isset( $paramList[2] ) ? $paramList[2] : null;

        if ( !isset( $fileContentCache[$source] ) )
        {
            if ( !$isQuiet )
                $cli->output( "Fetching URL: $source" );

            $fileContentCache[$source] = eZHTTPTool::getDataByURL( $source, false, eZStaticCache::USER_AGENT );
        }

        if ( $fileContentCache[$source] === false )
        {
            $cli->error( "Could not grab content from \"$source\", is the hostname correct and Apache running?" );

            if ( $invalid !== null )
            {
                $deleteParams[] = $param;

                continue;
            }

            $markInvalidParams[] = $param;
        }
        else
        {
            eZStaticCache::storeCachedFile( $destination, $fileContentCache[$source] );

            $deleteParams[] = $param;
        }
    }

    if ( !empty( $markInvalidParams ) )
    {
        $db->begin();
        $db->query( "UPDATE ezpending_actions SET param=( " . $db->concatString( array( "param", "',invalid'" ) ) . " ) WHERE param IN ( '" . implode( "','", $markInvalidParams ) . "' )" );
        $db->commit();
    }

    if ( !empty( $deleteParams ) )
    {
        $db->begin();
        $db->query( "DELETE FROM ezpending_actions WHERE action='static_store' AND param IN ( '" . implode( "','", $deleteParams ) . "' )" );
        $db->commit();
    }
    else
    {
        $offset += $limit;
    }
} while ( true );

if ( !$isQuiet )
    $cli->output( "Done" );

?>
