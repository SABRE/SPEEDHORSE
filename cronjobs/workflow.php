<?php
//
// Definition of Runcronworflows class
//
// Created on: <02-���-2002 14:04:21 sp>
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

$runInBrowser = true;
if ( isset( $webOutput ) )
    $runInBrowser = $webOutput;

$db = eZDB::instance();

$workflowProcessList = eZWorkflowProcess::fetchForStatus( eZWorkflow::STATUS_DEFERRED_TO_CRON );

if ( !$isQuiet )
    $cli->output( "Checking for workflow processes"  );
$removedProcessCount = 0;
$processCount = 0;
$statusMap = array();
foreach( $workflowProcessList as $process )
{
    $db->begin();

    $workflow = eZWorkflow::fetch( $process->attribute( "workflow_id" ) );

    if ( $process->attribute( "event_id" ) != 0 )
        $workflowEvent = eZWorkflowEvent::fetch( $process->attribute( "event_id" ) );
    $process->run( $workflow, $workflowEvent, $eventLog );
// Store changes to process

    ++$processCount;
    $status = $process->attribute( 'status' );
    if ( !isset( $statusMap[$status] ) )
        $statusMap[$status] = 0;
    ++$statusMap[$status];

    if ( $process->attribute( 'status' ) != eZWorkflow::STATUS_DONE )
    {
        if ( $process->attribute( 'status' ) == eZWorkflow::STATUS_RESET ||
             $process->attribute( 'status' ) == eZWorkflow::STATUS_FAILED ||
             $process->attribute( 'status' ) == eZWorkflow::STATUS_NONE ||
             $process->attribute( 'status' ) == eZWorkflow::STATUS_CANCELLED ||
             $process->attribute( 'status' ) == eZWorkflow::STATUS_BUSY
           )
        {
            $bodyMemento = eZOperationMemento::fetchMain( $process->attribute( 'memento_key' ) );
            $mementoList = eZOperationMemento::fetchList( $process->attribute( 'memento_key' ) );
            $bodyMemento->remove();
            foreach( $mementoList as $memento )
            {
                $memento->remove();
            }
        }

        if ( $process->attribute( 'status' ) == eZWorkflow::STATUS_CANCELLED )
        {
            ++$removedProcessCount;
            $process->removeThis();
        }
        else
        {
            $process->store();
        }
    }
    else
    {   //restore memento and run it
        $bodyMemento = eZOperationMemento::fetchChild( $process->attribute( 'memento_key' ) );
        if ( $bodyMemento === null )
        {
            eZDebug::writeError( $bodyMemento, "Empty body memento in workflow.php" );
            $db->commit();
            continue;
        }
        $bodyMementoData = $bodyMemento->data();
        $mainMemento = $bodyMemento->attribute( 'main_memento' );
        if ( !$mainMemento )
        {
            $db->commit();
            continue;
        }

        $mementoData = $bodyMemento->data();
        $mainMementoData = $mainMemento->data();
        $mementoData['main_memento'] = $mainMemento;
        $mementoData['skip_trigger'] = true;
        $mementoData['memento_key'] = $process->attribute( 'memento_key' );
        $bodyMemento->remove();
        $operationParameters = array();
        if ( isset( $mementoData['parameters'] ) )
            $operationParameters = $mementoData['parameters'];
        $operationResult = eZOperationHandler::execute( $mementoData['module_name'], $mementoData['operation_name'], $operationParameters, $mementoData );
        ++$removedProcessCount;
        $process->removeThis();
    }

    $db->commit();
}
if ( !$isQuiet )
{
    $cli->output( $cli->stylize( 'emphasize', "Status list" ) );
    $statusTextList = array();
    $maxStatusTextLength = 0;
    foreach ( $statusMap as $statusID => $statusCount )
    {
        $statusName = eZWorkflow::statusName( $statusID );
        $statusText = "$statusName($statusID)";
        $statusTextList[] = array( 'text' => $statusText,
                                   'count' => $statusCount );
        if ( strlen( $statusText ) > $maxStatusTextLength )
            $maxStatusTextLength = strlen( $statusText );
    }
    foreach ( $statusTextList as $item )
    {
        $text = $item['text'];
        $count = $item['count'];
        $cli->output( $cli->stylize( 'success', $text ) . ': ' . str_repeat( ' ', $maxStatusTextLength - strlen( $text ) ) . $cli->stylize( 'emphasize', $count )  );
    }
    $cli->output();
    $cli->output( $cli->stylize( 'emphasize', $removedProcessCount ) . " out of " . $cli->stylize( 'emphasize', $processCount ) . " processes was finished"  );
}

?>
