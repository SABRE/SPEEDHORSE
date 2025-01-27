<?php
//
// Definition of siblocksupdate cronjob
//
// Created on: <28-Apr-2008 10:06:19 jr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
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

$ini                     = eZINI::instance( 'site.ini' );
$avalaibleSiteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
$viewCaching             = $ini->variable( 'ContentSettings', 'ViewCaching' );
$indexPage               = $ini->variable( 'SiteSettings', 'IndexPage' );

$ini                     = eZINI::instance( 'ezsi.ini' );
$SIBlockHandler          = $ini->variable( 'SIBlockSettings', 'BlockHandler' );
$forceRegenerationString = $ini->variable( 'TemplateFunctionSettings', 'ForceRegenerationString' );
$forceRegenerationValue  = $ini->variable( 'TemplateFunctionSettings', 'ForceRegenerationValue' );
$hostMatchMapItems       = $ini->variable( 'HostSettings'            , 'HostMatchMapItems' );
$cronjobForUpdatesOnly   = strtolower( $ini->variable( 'CronjobSettings', 'CronjobForUpdatesOnly' ) ) == 'yes' ? true : false;

if( !$cronjobForUpdatesOnly )
{
    $cli->error( 'Cronjob disable, cf ezsi.ini/[CronjobSettings]/CronjobForUpdatesOnly' );
    return;
}


$hostMapList = array();
foreach( $hostMatchMapItems as $mapItems )
{
    list( $hostName, $siteAccessName ) = explode( ';', $mapItems );
    $hostMapList[strtolower( $siteAccessName )] = $hostName;
}

$cli->output( 'Finding expired blocks' );

$db   = eZDB::instance();
$sql  = 'SELECT * FROM ezsi_files WHERE (' . time() . ' - mtime) >= ttl ORDER BY TTL DESC';
$rows = $db->arrayQuery( $sql );

$updatedPageList = array();

foreach( $rows as $expiredBlock )
{
    if( in_array( $expiredBlock['siteaccess'], $avalaibleSiteAccessList ) )
    {
        $pageURL = 'http://' . $hostMapList[ strtolower( $expiredBlock['siteaccess'] )] . '/';

        $pageURL .= $expiredBlock['urlalias'];

        // the page has already been called so everything has
        // already been updated and viewcache has already been purged
        if( in_array( $pageURL, $updatedPageList ) )
            continue;

        // flushing content cache for this page if needed
        if( $viewCaching == 'enabled' )
        {
            $urlAlias = $expiredBlock['urlalias'];

            // index page
            if( strlen( $urlAlias ) == 0 )
            {
                if( $indexPage[0] == '/' )
                {
                    $indexPage = substr( $indexPage, 1 );
                }

                $urlAlias = $indexPage;
            }

            $destinationURLArray = explode( '/', $urlAlias );

            // do we need to clear ViewCache ?
            // content/view/<viewmode>/<nodeID>
            if( isset( $destinationURLArray[3] ) )
            {
                $nodeID = $destinationURLArray[3];

                // 0 => false
                // 1 => true or top level node
                // you do not want that
                if( is_numeric( $nodeID ) and $nodeID > 1 )
                {
                    eZContentCache::cleanup( array( $nodeID ) );
                    eZDebug::writeNotice( 'Clearing ViewCache for object ' . $nodeID, 'eZSIBlockFunction::process' );
                }
            }
        }

        $cli->output( 'Calling ' . $cli->stylize( 'emphasize', $pageURL ) . ' : ', false );
        $updatedPageList[] = $pageURL;

        // regenerating si blocks by calling the page
        // storing the results is useless
        if( !callPage( $pageURL ) )
        {
            $cli->output( $cli->stylize( 'red', 'FAILED' ) );
            removeFileIfNeeded( $expiredBlock, $db);
        }
        else
        {
            $sql  = "SELECT mtime FROM ezsi_files WHERE namehash = '" . $expiredBlock['namehash'] . "'";
            $rows = $db->arrayQuery( $sql );

            if( $rows[0]['mtime'] > $expiredBlock['mtime'] )
            {
                $cli->output( $cli->stylize( 'green', 'SUCCESS' ) );
            }
            else
            {
                $cli->output( $cli->stylize( 'emphasize', 'CHECKING IF REMOVAL IS NEEDED' ) );
                removeFileIfNeeded( $expiredBlock, $db);
            }
        }
    }
    else
    {
        eZDebug::writeError( 'Use of an undefined siteaccess : ' . $expiredBlock['siteaccess'], 'SI Block Update Cronjob' );
    }
}

function removeFileIfNeeded( $expiredBlock, $db )
{
    $ini                    = eZINI::instance( 'ezsi.ini' );
    $deleteSIBlockOnFailure = $ini->variable( 'CronjobSettings', 'DeleteSIBlockOnFailure' );

    $fileHandler = eZSIBlockFunction::loadSIFileHandler();

    if( $deleteSIBlockOnFailure == 'enabled' )
    {
        $sql = "DELETE FROM ezsi_files WHERE namehash = '" . $expiredBlock['namehash'] . "'";

        if( $db->query( $sql ) )
        {
            $pathInfo = pathinfo( $expiredBlock['filepath'] );
            if( !$fileHandler->removeFile( $pathInfo['dirname'], $pathInfo['basename'] ) )
            {
                eZDebug::writeError( 'Removing of SI block ' . $expiredBlock['filepath'] . ' failed' );
            }
        }
        else
        {
            eZDebug::writeError( 'Unable to remove the SI block row ' . $expiredBlock['namehash'] . ' from the database' );
        }
    }
}

function callPage( $URL )
{
    $ini       = eZINI::instance( 'ezsi.ini' );
    $userAgent = $ini->variable( 'CronjobSettings', 'UserAgentName' );

    $optionList = array( CURLOPT_URL            => $URL,
                         CURLOPT_TIMEOUT        => 10,
                         CURLOPT_RETURNTRANSFER => true,
                         CURLOPT_NOBODY         => true );

    if( $ini->variable( 'CronjobSettings', 'CronjobForUpdatesOnly' ) == 'yes' )
        $optionList[CURLOPT_USERAGENT] = $userAgent;

    $ch = curl_init();
    if( !curl_setopt_array( $ch, $optionList ) )
        return false;

    $result = curl_exec( $ch );

    curl_close( $ch );

    if( $result != false or $result == "" ) // cf CURLOPT_NOBODY => true
        return true;

    return false;
}
?>
