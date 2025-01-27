<?php
//
// Created on: <19-Sep-2002 16:45:08 kk>
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

$Module = $Params['Module'];

if ( !isset ( $Params['RSSFeed'] ) )
{
    eZDebug::writeError( 'No RSS feed specified' );
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$feedName = $Params['RSSFeed'];
$RSSExport = eZRSSExport::fetchByName( $feedName );

// Get and check if RSS Feed exists
if ( !$RSSExport )
{
    eZDebug::writeError( 'Could not find RSSExport : ' . $Params['RSSFeed'] );
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$config = eZINI::instance( 'site.ini' );
$cacheTime = intval( $config->variable( 'RSSSettings', 'CacheTime' ) );

$lastModified = gmdate( 'D, d M Y H:i:s', time() ) . ' GMT';

if ( $cacheTime <= 0 )
{
    // use the new attribute rss-xml-content instead of the deprecated attribute rss-xml
    // it returns the RSS as an XML string instead of a DomDocument object
    $xmlDoc = $RSSExport->attribute( 'rss-xml-content' );
    $rssContent = $xmlDoc;
}
else
{
    $cacheDir = eZSys::cacheDirectory();
    $currentSiteAccessName = $GLOBALS['eZCurrentAccess']['name'];
    $cacheFilePath = $cacheDir . '/rss/' . md5( $currentSiteAccessName . $feedName ) . '.xml';

    if ( !is_dir( dirname( $cacheFilePath ) ) )
    {
        eZDir::mkdir( dirname( $cacheFilePath ), false, true );
    }

    $cacheFile = eZClusterFileHandler::instance( $cacheFilePath );

    if ( !$cacheFile->exists() or ( time() - $cacheFile->mtime() > $cacheTime ) )
    {
        // use the new attribute rss-xml-content instead of the deprecated attribute rss-xml
        // it returns the RSS as an XML string instead of a DomDocument object
        $xmlDoc = $RSSExport->attribute( 'rss-xml-content' );
        // Get current charset
        $charset = eZTextCodec::internalCharset();
        $rssContent = trim( $xmlDoc );
        $cacheFile->storeContents( $rssContent, 'rsscache', 'xml' );
    }
    else
    {
        $lastModified = gmdate( 'D, d M Y H:i:s', $cacheFile->mtime() ) . ' GMT';

        if( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
        {
            $ifModifiedSince = $_SERVER['HTTP_IF_MODIFIED_SINCE'];

            // Internet Explorer specific
            $pos = strpos($ifModifiedSince,';');
            if ( $pos !== false )
                $ifModifiedSince = substr( $ifModifiedSince, 0, $pos );

            if( strcmp( $lastModified, $ifModifiedSince ) == 0 )
            {
                header( 'HTTP/1.1 304 Not Modified' );
                header( 'Last-Modified: ' . $lastModified );
                header( 'X-Powered-By: eZ Publish' );
                eZExecution::cleanExit();
           }
        }
        $rssContent = $cacheFile->fetchContents();
    }
}

// Set header settings
$httpCharset = eZTextCodec::httpCharset();
header( 'Last-Modified: ' . $lastModified );

if ( $RSSExport->attribute( 'rss_version' ) === 'ATOM' )
    header( 'Content-Type: application/xml; charset=' . $httpCharset );
else
    header( 'Content-Type: application/rss+xml; charset=' . $httpCharset );

header( 'Content-Length: ' . strlen( $rssContent ) );
header( 'X-Powered-By: eZ Publish' );

while ( @ob_end_clean() );

echo $rssContent;

eZExecution::cleanExit();


?>
