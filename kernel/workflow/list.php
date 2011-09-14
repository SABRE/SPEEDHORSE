<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
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

// $execStack = eZExecutionStack::instance();
// $execStack->clear();
// $execStack->addEntry( $Module->functionURI( 'list' ),
//                       $Module->attribute( 'name' ), 'list' );

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( 'NewGroupButton' ) )
    return $Module->run( 'groupedit', array() );
if ( $http->hasPostVariable( 'NewWorkflowButton' ) )
    return $Module->run( 'edit', array() );

if ( $http->hasPostVariable( 'DeleteButton' ) and
     $http->hasPostVariable( 'Workflow_id_checked' ) )
{
    eZWorkflow::setIsEnabled( false, $http->postVariable( 'Workflow_id_checked' ) );
}

$groupList = eZWorkflowGroup::fetchList();
$workflows = eZWorkflow::fetchList();
$workflowList = array();
foreach( $workflows as $workflow )
{
    $workflowList[$workflow->attribute( 'id' )] = $workflow;
}

$Module->setTitle( 'Workflow list' );


$tpl = eZTemplate::factory();

$tpl->setVariable( 'workflow_list', $workflowList );
$tpl->setVariable( 'group_list', $groupList );
$tpl->setVariable( 'module', $Module );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:workflow/list.tpl' );
$Result['path'] = array( array( 'url' => '/workflow/list/',
                                'text' => ezpI18n::tr( 'kernel/workflow', 'Workflow list' ) ) );

?>
