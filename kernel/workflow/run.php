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

// $execStack = eZExecutionStack::instance();
// $execStack->addEntry( $Module->functionURI( "run" ) . "/" . $WorkflowProcessID,
//                       $Module->attribute( "name" ), "run" );

// Template handling

$tpl = eZTemplate::factory();

$workflow = eZWorkflow::fetch( $process->attribute( "workflow_id" ) );

$workflowEvent = null;
if ( $process->attribute( "event_id" ) != 0 )
    $workflowEvent = eZWorkflowEvent::fetch( $process->attribute( "event_id" ) );

$process->run( $workflow, $workflowEvent, $eventLog );
// Store changes to process
if ( $process->attribute( 'status' ) != eZWorkflow::STATUS_DONE )
{
    $process->store();
}
if ( $process->attribute( 'status' ) == eZWorkflow::STATUS_DONE )
{
//    list ( $module, $function, $parameters ) = $process->getModuleInfo();
}
$tpl->setVariable( "event_log", $eventLog );
$tpl->setVariable( "current_workflow", $workflow );

$Module->setTitle( "Workflow run" );

$tpl->setVariable( "process", $process );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );

$Result = array();
$Result['content'] = $tpl->fetch( "design:workflow/run.tpl" );

?>
