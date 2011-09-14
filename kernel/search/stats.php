<?php
//
// Created on: <08-Aug-2002 14:04:07 bf>
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


if ( eZPreferences::value( 'admin_search_stats_limit' ) )
{
    switch ( eZPreferences::value( 'admin_search_stats_limit' ) )
    {
        case '2': { $limit = 25; } break;
        case '3': { $limit = 50; } break;
        default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}

$offset = $Params['Offset'];
if ( !is_numeric( $offset ) )
{
    $offset = 0;
}

$http = eZHTTPTool::instance();
$module = $Params['Module'];

if ( $module->isCurrentAction( 'ResetSearchStats' ) )
{
    eZSearchLog::removeStatistics();
}

$viewParameters = array( 'offset' => $offset, 'limit'  => $limit );
$tpl = eZTemplate::factory();

$db = eZDB::instance();
$query = "SELECT count(*) as count FROM ezsearch_search_phrase";
$searchListCount = $db->arrayQuery( $query );

$mostFrequentPhraseArray = eZSearchLog::mostFrequentPhraseArray( $viewParameters );

$tpl->setVariable( "view_parameters", $viewParameters );
$tpl->setVariable( "most_frequent_phrase_array", $mostFrequentPhraseArray );
$tpl->setVariable( "search_list_count", $searchListCount[0]['count'] );

$Result = array();
$Result['content'] = $tpl->fetch( "design:search/stats.tpl" );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/search', 'Search stats' ),
                                'url' => false ) );

?>
