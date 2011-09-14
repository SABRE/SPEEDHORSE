<?php
//
// Created on: <01-Jul-2002 17:06:14 amos>
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

$WorkflowProcessID = null;
if ( !isset( $Params["WorkflowProcessID"] ) )
{
    $Module->redirectTo( $Module->functionURI( "list" ) );
    return;
}

$WorkflowProcessID = $Params["WorkflowProcessID"];

$process = eZWorkflowProcess::fetch( $WorkflowProcessID );
if ( $process === null )
{
    $Module->redirectTo( $Module->functionURI( "list" ) );
    return;
}

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( "Reset" ) )
{
    $process->reset();
    $process->setAttribute( "modified", time() );
    $process->store();
}

// Template handling

$tpl = eZTemplate::factory();

$workflow = eZWorkflow::fetch( $process->attribute( "workflow_id" ) );
$workflowEvent = false;
if ( $process->attribute( "event_id" ) != 0 )
    $workflowEvent = eZWorkflowEvent::fetch( $process->attribute( "event_id" ) );

$lastEventStatus = $process->attribute( "last_event_status" );

if ( $http->hasPostVariable( "RunProcess" ) )
{
//     $Module->redirectTo( $Module->functionURI( "process" ) . "/" . $WorkflowProcessID );
//     return;
    if ( $workflowEvent instanceof eZWorkflowEvent )
    {
        $eventType = $workflowEvent->eventType();
        $lastEventStatus = $eventType->execute( $process, $workflowEvent );
    }
    $event_pos = $process->attribute( "event_position" );
    $next_event_pos = $event_pos + 1;
    $next_event_id = $workflow->fetchEventIndexed( $next_event_pos );
    if ( $next_event_id !== null )
    {
        $process->advance( $next_event_id, $next_event_pos, $lastEventStatus  );
        $workflowEvent = eZWorkflowEvent::fetch( $next_event_id );
    }
    else
    {
        unset( $workflowEvent );
        $workflowEvent = false;
        $process->advance();
    }
    $process->setAttribute( "modified", time() );
    $process->store();
}
$tpl->setVariable( "event_status", eZWorkflowType::statusName( $lastEventStatus ) );
$tpl->setVariable( "current_workflow", $workflow );
$tpl->setVariable( "current_event", $workflowEvent );

$Module->setTitle( "Workflow process" );

$tpl->setVariable( "process", $process );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );

$Result = array();
$Result['content'] = $tpl->fetch( "design:workflow/process.tpl" );


?>
