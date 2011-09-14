<?php
//
// Created on: <23-Feb-2006 12:33:41 hovik>
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

/*! \file syncnetwork.php
*/

@ini_set( 'memory_limit', '512M' );
$GLOBALS['eZDebugEnabled'] = false;

require 'extension/ez_network/classes/include_all.php';

if ( !$isQuiet )
{
    $cli->output( 'Starting eZ Network syncronization.' . "\n" .
                  'Use the --clear-all to reset client side data.' );
}

$clearAll = false;
foreach( $GLOBALS['argv'] as $argument )
{
    if ( $argument === '--clear-all' )
    {
        $clearAll = true;
    }
}

// Make sure network extensions is up to date.
$clientInfo = eZNetClientInfo::instance();
if ( !$clientInfo->validate() )
{
    return;
}

if ( $clearAll )
{
    $cli->output( 'Clearing existing data.' );
    $clientInfo->clearDB();
}

$classList = array( 'eZNetBranch',
                    'eZNetPatch',
                    'eZNetPatchItem',
                    'eZNetInstallation',
                    'eZNetModuleInstallation',
                    'eZNetModuleBranch',
                    'eZNetModulePatch',
                    'eZNetModulePatchItem',
                    'eZNetMonitorItem',
                    'eZNetMonitorGroup' );

$syncINI = eZINI::instance( 'sync.ini' );
$Server = $syncINI->variable( 'NetworkSettings', 'Server' );
$Port = $syncINI->variable( 'NetworkSettings', 'Port' );
$Path = $syncINI->variable( 'NetworkSettings', 'Path' );

// If use of SSL fails the client must attempt to use HTTP
$Port = eZNetSOAPSync::getPort( $Server, $Path, $Port );

$client = new eZSOAPClient( $Server, $Path, $Port );

$networkInfo = eZNetUtils::extensionInfo( 'ez_network' );
$cli->output( $networkInfo['name'] . ' client ' . $networkInfo['version'] );
$cli->output( '' );

// Initialize Soap sync manager, and start syncronization.
$syncManager = new eZNetSOAPSyncManager( $client,
                                         $classList,
                                         $cli );
$syncManager->syncronizeClient();

/* Check existence of eZNetInstallation object in current DB.
 * If the first synchronizing was not successful then eZNetIntsllation doesn't exist.
 * We should notify the user about that.
 * NOTE: Failed synchronizing can be caused through incorrect installation key.
 */

// If eZNetInstallation should not be synchronized We should not check the existence.
if ( !in_array( 'eZNetInstallation', $classList ) )
    return;

$hostID = eZNetSOAPSync::hostID();
$installation = eZNetInstallation::fetchBySiteID( $hostID );
if ( !$installation )
{
    $cli->output( "\n".
                  'eZNetInstallation object with the installation key (' . $hostID . ') was not found in the database.' . "\n" .
                  'Make sure the installation key is correct and has been sent to eZ Systems' . "\n" .
                  'or contact your system administrator.' );
}


?>
