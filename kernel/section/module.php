<?php
//
// Created on: <27-Aug-2002 15:41:43 bf>
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

$Module = array( 'name' => 'eZSection' );

$ViewList = array();
$ViewList['list'] = array(
    'functions' => array( 'view or edit or assign' ),
    'script' => 'list.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    "unordered_params" => array( "offset" => "Offset" ),
    'params' => array( ) );

$ViewList['view'] = array(
    'functions' => array( 'view or assign' ),
    'script' => 'view.php',
    'ui_context' => 'view',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( 'SectionID' ),
    'unordered_params' => array( 'offset' => 'Offset' ) );

$ViewList['edit'] = array(
    'functions' => array( 'edit' ),
    'script' => 'edit.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( 'SectionID' ) );

$ViewList['assign'] = array(
    'functions' => array( 'assign' ),
    'script' => 'assign.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    'params' => array( 'SectionID' ),
    'functions' => array( 'assign' ) );



$ClassID = array(
    'name'=> 'Class',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezcontentclass.php',
    'class' => 'eZContentClass',
    'function' => 'fetchList',
    'parameter' => array( 0, false, false, array( 'name' => 'asc' ) )
    );

$NewSectionID = array(
    'name'=> 'NewSection',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezsection.php',
    'class' => 'eZSection',
    'function' => 'fetchList',
    'parameter' => array( false )
    );

$SectionID = array(
    'name'=> 'Section',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezsection.php',
    'class' => 'eZSection',
    'function' => 'fetchList',
    'parameter' => array( false )
    );

$Assigned = array(
    'name'=> 'Owner',
    'values'=> array(
        array(
            'Name' => 'Self',
            'value' => '1')
        )
    );

$FunctionList = array();
$FunctionList['assign'] = array( 'Class' => $ClassID,
                                 'Section' => $SectionID,
                                 'Owner' => $Assigned,
                                 'NewSection' => $NewSectionID );
$FunctionList['edit'] = array();
$FunctionList['view'] = array();

?>
