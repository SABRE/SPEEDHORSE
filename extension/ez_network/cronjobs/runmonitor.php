<?php
//
// Created on: <06-Jul-2005 16:33:37 hovik>
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

/*! \file runmonitor.php
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

// Output current eZ Network version
$networkInfo = eZNetUtils::extensionInfo( 'ez_network' );
$cli->output( $networkInfo['name'] . ' client ' . $networkInfo['version'] );
$cli->output( '' );

if ( !$isQuiet )
{
    $cli->output( 'Starting eZ Network monitor.' . "\n" .
                  'Use the --force option to force monitors to be run.' );
}

$forceMonitors = false;
$removeAllOldData = false;
$datasetRate = false;

foreach( $GLOBALS['argv'] as $argument )
{
    if ( $argument == '--force' )
    {
        $forceMonitors = true;
    }
    elseif ( $argument == '--remove-all-old-monitor-data' )
    {
        $removeAllOldData = true;
    }
    elseif ( substr( $argument, 0, 15 ) == '--dataset-rate=' )
    {
        $datasetRate = substr( $argument, 15, strlen( $argument ) );
    }
}

$hostID = eZNetSOAPSync::hostID();

/* Check existence of eZNetInstallation object in current DB.
 * This checking is performed in eZNetMonitorResult::create() as well
 * but we should clearly understand why we cannot fetch monitor results
 * and should show an error.
 */
$installation = eZNetInstallation::fetchBySiteID( $hostID );
if ( !$installation )
{
    $cli->output( 'eZNetInstallation object with the installation key (' . $hostID . ') was not found in the database.' . "\n" .
                  'Make sure the installation key has been sent to eZ Systems and that the cronjob part "sync_network" has been run' . "\n" .
                  'or contact your system administrator.' );
    return;
}

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

if ( !$response or $response->isFault() )
{
    $cli->output( 'Failed connecting to eZ Systems servers to get global timestamp.' );
    return '';
}
$globalTS = $response->value();
$diffTS = $globalTS - time();
$cli->output( 'Offset from global TS: ' . $diffTS );


// Create monitor result.
$monitorResult = eZNetMonitorResult::create( $diffTS );
if ( !$monitorResult )
{
    /* eZNetMonitorResult::create() contains checking of eZNetInstallation existence and if it fails 'false' will be returned.
     * So in this case this part will never be called because we have already checked eZNetInstallation existence above.
     * But we stored this part because we should prevent other problems with creating monitor result.
     */
    $cli->output( 'Failed to create monitor result object.' );
    return;
}

$db = eZDB::instance();
$db->begin();

if ( !$removeAllOldData )
{
    $monitorResult->setDiffTS( $diffTS );
    $monitorResult->store();

    // Run monitors.
    $groupOffset = 0;
    $groupLimit = 10;
    $monitorItemCount = 0;

    while( $monitorGroupList = eZNetMonitorGroup::fetchListByBranchID( $installation->attribute( 'branch_id' ),
                                                                       $groupOffset,
                                                                       $groupLimit ) )
    {
        $groupOffset += $groupLimit;
        foreach( $monitorGroupList as $monitorGroup )
        {
            $monitorGroup->setDiffTS( $diffTS );
            $lastRunTS = $forceMonitors ? 0 : $monitorGroup->attribute( 'last_run_ts' );
            $itemOffset = 0;
            $itemLimit = 10;
            while( $monitorItemList = $monitorGroup->fetchMonitorItemList( $itemOffset,
                                                                           $itemLimit,
                                                                           $lastRunTS ) )
            {
                $itemOffset += $itemLimit;
                foreach( $monitorItemList as $monitorItem )
                {
                    $functionData = substr( trim( $monitorItem->attribute( 'function_data' ) ), 5, -2 );

                    // Check if the class is already loaded.
                    $classNameMatch = array();
                    preg_match( '/class ([a-zA-Z0-9_]*) extends/', $functionData, $classNameMatch );
                    $className = isset( $classNameMatch[1] ) ? $classNameMatch[1] : false;
                    if ( $className and class_exists( $className ) )
                    {
                        continue;
                    }

                    $monitorItemResult = eval( $functionData );
                    if ( $monitorItemResult )
                    {
                        ++$monitorItemCount;
                        $monitorItemResult->setAttribute( 'monitor_result_id', $monitorResult->attribute( 'id' ) );
                        $monitorItemResult->setAttribute( 'monitor_item_id', $monitorItem->attribute( 'id' ) );
                        $monitorItemResult->setAttribute( 'created', time() + $diffTS );
                        $monitorItemResult->run( $cli, $script );
                        $monitorItemResult->setDiffTS( $diffTS );
                        $monitorItemResult->store();
                    }
                }
            }
        }
    }

    $monitorResult->setAttribute( 'finnished', time() + $diffTS );
    $monitorResult->sync();

    $cli->output( 'Monitor finished.' );

    if ( $monitorItemCount == 0 )
    {
        $cli->output( 'No monitor items run.' );
        $db->rollback();
        return;
    }

    $cli->output( 'Sending result to ez.no.' );
    // Send monitor result to ez.no
    $classSyncPushList = array( 'eZNetMonitorResult',
                                'eZNetMonitorResultValue' );

    $classSyncOrder = eZNetSOAPSyncAdvanced::orderClassListByDependencies( $classSyncPushList );

    // Syncronize all classes.
    foreach( $classSyncOrder as $className )
    {
        $messageSync = new eZNetSOAPSyncClient( call_user_func( array( $className, 'definition' ) ) );
        $cli->output( 'Sending: ' . $className );
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
}

$monINI = eZINI::instance( 'networkmonitor.ini' );
$expiredInDays = $monINI->hasVariable( 'CleanUpSettings', 'MonitorDaysExpiry' ) ? $monINI->variable( 'CleanUpSettings', 'MonitorDaysExpiry' ) : 0;
if ( is_numeric( $expiredInDays ) and $expiredInDays > 0 )
{
    // If datasetRate has not been defined from args fetch it from ini
    if ( $datasetRate === false )
    {
        $datasetRate = $monINI->hasVariable( 'CleanUpSettings', 'RemoveOldDatasetRate' ) ? $monINI->variable( 'CleanUpSettings', 'RemoveOldDatasetRate' ) : 10000;
    }

    // If 'remove all old data' is set (by argv) we should reset $datasetRate
    $datasetRate = ( $removeAllOldData or $datasetRate == 0 ) ? false : $datasetRate;

    if ( !$isQuiet )
    {
        $datasetRateStr = $datasetRate !== false ? $datasetRate : 'all';
        $cli->output( 'Cleanuping ' . $datasetRateStr . ' expired monitor results older than ' . $expiredInDays . ' days.' );
    }

    // Create timestamp, we remove data older than this timestamp
    $expiredTS = time() + $diffTS - 60 * 60 * 24 * $expiredInDays;

    // Clean up expired results and data from ezx_ezpnet_mon_result and ezx_ezpnet_mon_value
    eZNetMonitorResult::cleanup( $expiredTS, $datasetRate );
}

$db->commit();

$cli->output( 'Finished sending result.' );

?>
