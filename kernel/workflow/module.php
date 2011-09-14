<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
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

$Module = array( "name" => "eZWorkflow" );

$ViewList = array();
$ViewList["view"] = array(
    "script" => "view.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowID" ) );
$ViewList["edit"] = array(
    "script" => "edit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowID", "GroupID", "GroupName" ) );
$ViewList["groupedit"] = array(
    "script" => "groupedit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowGroupID" ) );
$ViewList["down"] = array(
    "script" => "edit.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowID", "EventID" ) );
$ViewList["up"] = array(
    "script" => "edit.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowID", "EventID" ) );
$ViewList["workflowlist"] = array(
    "script" => "workflowlist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "GroupID" ) );
$ViewList["grouplist"] = array(
    "script" => "grouplist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array() );
$ViewList["process"] = array(
    "script" => "process.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowProcessID" ) );
$ViewList["run"] = array(
    "script" => "run.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowProcessID" ) );
$ViewList["event"] = array(
    "script" => "event.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "WorkflowID", "EventID" ) );
$ViewList["processlist"] = array(
    "script" => "processlist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    'unordered_params' => array( 'offset' => 'Offset' ),
    "params" => array( ) );

?>
