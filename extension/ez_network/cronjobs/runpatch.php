<?php
//
// Created on: <13-Jul-2005 15:47:04 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Network
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
//     Att: eZ Systems AS Licensing Dept., Klostergata 30, N-3732 Skien, Norway
//
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file runpatch.php
*/

@ini_set( 'memory_limit', '512M' );
$GLOBALS['eZDebugEnabled'] = false;

require 'extension/ez_network/classes/include_all.php';

// Make sure network extensions is up to date.
$clientInfo = eZNetClientInfo::instance();
if ( !$clientInfo->validate() )
{
    return;
}

// Output --update-all options.
if ( !$isQuiet )
{
    $cli->output( 'Starting eZ Network patching.' . "\n" .
                  'Use the --update-all option to resend all patch statuses to ez.no.' );
}
// If current execution is the first run after an update and we should drop
$updateAll = $clientInfo->isFirstRun() ? true : false;
foreach( $GLOBALS['argv'] as $argument )
{
    if ( $argument === '--update-all' )
    {
        $updateAll = true;
    }
}

// Output current eZ Network version
$networkInfo = eZNetUtils::extensionInfo( 'ez_network' );
$cli->output( $networkInfo['name'] . ' client ' . $networkInfo['version'] );
$cli->output( '' );

// Get global timestamp
$cli->output( 'Getting global timestamp from eZ Systems' );
$syncINI = eZINI::instance( 'sync.ini' );
$Server = $syncINI->variable( 'NetworkSettings', 'Server' );
$Port = $syncINI->variable( 'NetworkSettings', 'Port' );
$Path = $syncINI->variable( 'NetworkSettings', 'Path' );

// If use of SSL fails the client must attempt to use HTTP
$Port = eZNetSOAPSync::getPort( $Server, $Path, $Port );

$client = new eZSOAPClient( $Server, $Path, $Port );
$request = new eZSOAPRequest( 'eZNetMonSOAPTools__timestamp', 'eZNetNS' );
$response = $client->send( $request );
if ( $response->isFault() )
{
    $cli->output( 'Failed connecting to eZ Systems servers to get global timestamp: ' . $response->faultString() );
    return '';
}
$globalTS = $response->value();
$diffTS = $globalTS - time();
$cli->output( 'Offset from global TS: ' . $diffTS );


// Start installing missing patches.
if ( !$isQuiet )
{
    $cli->output( "Starting eZ Publish Network patching." );
}

// Install eZ Publish patches
$nextItem = true;
eZNetPatchItem::logeZPublishPatchsStatus( "Installing" );
while( $nextItem )
{
    $previousItem = $nextItem;
    $nextItem = eZNetPatchItem::fetchNextPatchItem();
    if ( $nextItem )
    {
        // If a patch fails, it'll return the same patch as "next item".
        if ( $previousItem !== true &&
             $nextItem->attribute( 'id' ) == $previousItem->attribute( 'id' ) )
        {
            $nextItem = false;
        }
        else
        {
            if( !$isQuiet )
            {
                $eZPubPatch = $nextItem->attribute( 'patch' );
                $cli->output( "Installing: " . $eZPubPatch->attribute( "name" ) . " patch-item id: " . $nextItem->attribute( "id" ) . "\n" );
            }
            $nextItem->setDiffTS( $diffTS );
            $nextItem->install();
        }
    }
}
if ( $latestInstalled = eZNetPatchItem::fetchLatestInstalled() )
{
    $latestInstalled->setDiffTS( $diffTS );
    $latestInstalled->makePreviousObsolete();
}
eZNetPatchItem::logeZPublishPatchsStatus( "Completed" );

// Install eZ Publish - module patches
foreach( eZNetModuleBranch::fetchListBySiteID( eZNetUtils::hostID() ) as $moduleBranch )
{
    $nextItem = true;
    while( $nextItem )
    {
        $previousItem = $nextItem;
        $nextItem = eZNetModulePatchItem::fetchNextPatchItem( $moduleBranch->attribute( 'id' ) );

        if ( $nextItem )
        {
            // If a patch fails, it'll return the same patch as "next item".
            if ( $previousItem !== true &&
                 $nextItem->attribute( 'id' ) == $previousItem->attribute( 'id' ) )
            {
                $nextItem = false;
            }
            else
            {
                $nextItem->setDiffTS( $diffTS );
                $nextItem->install();
            }
        }
    }
    if ( $latestInstalled = eZNetModulePatchItem::fetchLatestInstalled( $moduleBranch->attribute( 'id' ) ) )
    {
        $latestInstalled->setDiffTS( $diffTS );
        $latestInstalled->makePreviousObsolete();
    }
}

if ( $updateAll )
{
    eZNetPatchItem::updateModifiedAll( $diffTS );
    eZNetModulePatchItem::updateModifiedAll( $diffTS );
    // If current execution was the first run after an update and we should drop "first run key"
    $clientInfo->dropFirstRun();
}


if ( !$isQuiet )
{
    $cli->output( "Finished updating patches." );
}

// Update patch status to ez.no
$classSyncOrder = eZNetSOAPSyncAdvanced::orderClassListByDependencies( array( 'eZNetPatchItem',
                                                                              'eZNetModulePatchItem' ) );

foreach( $classSyncOrder as $className )
{
    $messageSync = new eZNetSOAPSyncClient( call_user_func( array( $className, 'definition' ) ) );
    $result = $messageSync->syncronizePushClient( $client );
    if ( !$result )
    {
        $cli->output( 'Syncronization of: ' . $className . ' failed. See error log for more information' );
    }
    else
    {
        $cli->output( 'Exported : ' . $result['export_count'] . ' elements to Class : ' . $result['class_name'] );
    }
}

?>
