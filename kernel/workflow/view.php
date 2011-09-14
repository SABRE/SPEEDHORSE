<?php
//
// Created on: <20-Sep-2004 15:11:32 jk>
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
$http = eZHTTPTool::instance();
$validation = array( 'processed' => false,
                     'groups' => array() );

$WorkflowID = $Params["WorkflowID"];
$WorkflowID = (int) $WorkflowID;
if ( !is_int( $WorkflowID ) )
    $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );

$workflow = eZWorkflow::fetch( $WorkflowID );
if ( !$workflow )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( $http->hasPostVariable( "AddGroupButton" ) && $http->hasPostVariable( "Workflow_group") )
{
    $selectedGroup = $http->postVariable( "Workflow_group" );
    eZWorkflowFunctions::addGroup( $WorkflowID, 0, $selectedGroup );
}
if ( $http->hasPostVariable( "DeleteGroupButton" ) && $http->hasPostVariable( "group_id_checked" ) )
{
    $selectedGroup = $http->postVariable( "group_id_checked" );
    if ( !eZWorkflowFunctions::removeGroup( $WorkflowID, 0, $selectedGroup ) )
    {
        $validation['groups'][] = array( 'text' => ezpI18n::tr( 'kernel/workflow', 'You have to have at least one group that the workflow belongs to!' ) );
        $validation['processed'] = true;
    }
}

$event_list = $workflow->fetchEvents();

$tpl = eZTemplate::factory();
$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( "workflow", $workflow->attribute( "id" ) ) ) );

$tpl->setVariable( "workflow", $workflow );
$tpl->setVariable( "event_list", $event_list );
$tpl->setVariable( 'validation', $validation );

$Result = array();
$Result['content'] = $tpl->fetch( "design:workflow/view.tpl" );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/workflow', 'Workflow' ),
                                'url' => false ),
                         array( 'text' => ezpI18n::tr( 'kernel/workflow', 'View' ),
                                'url' => false ) );

?>
