<?php
//
// Definition of  class
//
// Created on: <07-Jul-2003 10:06:19 wy>
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
    $cli->output( "Checking link ..." );

$cronjobIni = eZINI::instance( 'cronjob.ini' );
$siteURLs = $cronjobIni->variable( 'linkCheckSettings', 'SiteURL' );
$linkList = eZURL::fetchList( array( 'only_published' => true ) );
foreach ( $linkList as $link )
{
    $linkID = $link->attribute( 'id' );
    $url = $link->attribute( 'url' );
    $isValid = $link->attribute( 'is_valid' );

    $cli->output( "check-" . $cli->stylize( 'emphasize', $url ) . " ", false );
    if ( preg_match("/^(http:)/i", $url ) or
         preg_match("/^(ftp:)/i", $url ) or
         preg_match("/^(https:)/i", $url ) or
         preg_match("/^(file:)/i", $url ) or
         preg_match("/^(mailto:)/i", $url ) )
    {
        if ( preg_match("/^(mailto:)/i", $url))
        {
            if ( eZSys::osType() != 'win32' )
            {
                $url = trim( preg_replace("/^mailto:(.+)/i", "\\1", $url));
                list($userName, $host) = explode( '@', $url );
                list($host, $junk) = explode( '?', $host );
                $dnsCheck = checkdnsrr( $host,"MX" );
                if ( !$dnsCheck )
                {
                    if ( $isValid )
                        eZURL::setIsValid( $linkID, false );
                    $cli->output( $cli->stylize( 'warning', "invalid" ) );
                }
                else
                {
                    if ( !$isValid )
                        eZURL::setIsValid( $linkID, true );
                    $cli->output( $cli->stylize( 'success', "valid" ) );
                }
            }
        }
        else if ( preg_match("/^(http:)/i", $url ) or
                  preg_match("/^(file:)/i", $url ) or
                  preg_match("/^(ftp:)/i", $url ) )
        {
            if ( !eZHTTPTool::getDataByURL( $url, true, 'eZ Publish Link Validator' ) )
            {
                if ( $isValid )
                    eZURL::setIsValid( $linkID, false );
                $cli->output( $cli->stylize( 'warning', "invalid" ) );
            }
            else
            {
                if ( !$isValid )
                    eZURL::setIsValid( $linkID, true );
                $cli->output( $cli->stylize( 'success', "valid" ) );
            }
        }
        else
        {
            $cli->output( "Couldn't check https protocol" );
        }
    }
    else
    {
        $translateResult = eZURLAliasML::translate( $url );

        if ( !$translateResult )
        {
              $isInternal = false;
              // Check if it is a valid internal link.
              foreach ( $siteURLs as $siteURL )
              {
                  $siteURL = preg_replace("/\/$/e", "", $siteURL );
                  $fp = @fopen( $siteURL . "/". $url, "r" );
                  if ( !$fp )
                  {
                      // do nothing
                  }
                  else
                  {
                      $isInternal = true;
                      fclose($fp);
                  }
              }
              $translateResult = $isInternal;
        }
        if ( $translateResult )
        {
            if ( !$isValid )
                eZURL::setIsValid( $linkID, true );
            $cli->output( $cli->stylize( 'success', "valid" ) );
        }
        else
        {
            if ( $isValid )
                eZURL::setIsValid( $linkID, false );
            $cli->output( $cli->stylize( 'warning', "invalid" ) );
        }
    }
    eZURL::setLastChecked( $linkID );
}

if ( !$isQuiet )
    $cli->output( "All links have been checked!" );

?>
