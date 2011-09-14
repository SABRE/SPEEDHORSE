<?php
//
// Created on: <23-Aug-2006 11:00:00 amos>
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
    $cli->output( "Cleaning up user's drafts..." );

// Cleaning up usual drafts
$ini = eZINI::instance( 'content.ini' );
$draftsCleanUpLimit = $ini->hasVariable( 'VersionManagement', 'DraftsCleanUpLimit' ) ?
                         $ini->variable( 'VersionManagement', 'DraftsCleanUpLimit' ) : 0;
$durationSetting = $ini->hasVariable( 'VersionManagement', 'DraftsDuration' ) ?
                      $ini->variable( 'VersionManagement', 'DraftsDuration' ) : array( 'days' => 90 );

$isDurationSet = false;
$duration = 0;
if ( is_array( $durationSetting ) )
{
    if ( isset( $durationSetting[ 'days' ] ) and is_numeric( $durationSetting[ 'days' ] ) )
    {
        $duration += $durationSetting[ 'days' ] * 60 * 60 * 24;
        $isDurationSet = true;
    }
    if ( isset( $durationSetting[ 'hours' ] ) and is_numeric( $durationSetting[ 'hours' ] ) )
    {
        $duration += $durationSetting[ 'hours' ] * 60 * 60;
        $isDurationSet = true;
    }
    if ( isset( $durationSetting[ 'minutes' ] ) and is_numeric( $durationSetting[ 'minutes' ] ) )
    {
        $duration += $durationSetting[ 'minutes' ] * 60;
        $isDurationSet = true;
    }
    if ( isset( $durationSetting[ 'seconds' ] ) and is_numeric( $durationSetting[ 'seconds' ] ) )
    {
        $duration += $durationSetting[ 'seconds' ];
        $isDurationSet = true;
    }
}

if ( $isDurationSet )
{
    $expiryTime = time() - $duration;
    $processedCount = eZContentObjectVersion::removeVersions( eZContentObjectVersion::STATUS_DRAFT, $draftsCleanUpLimit, $expiryTime );

    if ( !$isQuiet )
        $cli->output( "Cleaned up " . $processedCount . " drafts" );
}
else
{
    if ( !$isQuiet )
        $cli->output( "Lifetime is not set for user's drafts (see your ini-settings, content.ini, VersionManagement section)." );
}

?>
