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

$Module = array( "name" => "eZVisual",
                 "variable_params" => true,
                 'ui_component_match' => 'view' );

$ViewList = array();
$ViewList["toolbarlist"] = array(
    "script" => "toolbarlist.php",
    "default_navigation_part" => 'ezvisualnavigationpart',
    "params" => array( 'SiteAccess' ) );

$ViewList["toolbar"] = array(
    "script" => "toolbar.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezvisualnavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    'single_post_actions' => array( 'BackToToolbarsButton' => 'BackToToolbars',
                                    'NewToolButton' => 'NewTool',
                                    'UpdatePlacementButton' => 'UpdatePlacement',
                                    'BrowseButton' => 'Browse',
                                    'RemoveButton' => 'Remove',
                                    'StoreButton' => 'Store' ),
    "params" => array( 'SiteAccess', 'Position' ) );

$ViewList["menuconfig"] = array(
    "script" => "menuconfig.php",
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'SelectCurrentSiteAccessButton' => 'SelectCurrentSiteAccess' ),
    "params" => array() );

$ViewList["templatelist"] = array(
    "script" => "templatelist.php",
    "default_navigation_part" => 'ezvisualnavigationpart',
    "params" => array( ),
    "unordered_params" => array( "offset" => "Offset" ) );

$ViewList["templateview"] = array(
    "script" => "templateview.php",
    "default_navigation_part" => 'ezvisualnavigationpart',
    'single_post_actions' => array( 'SelectCurrentSiteAccessButton' => 'SelectCurrentSiteAccess',
                                    'RemoveOverrideButton' => 'RemoveOverride',
                                    'UpdateOverrideButton' => 'UpdateOverride',
                                    'NewOverrideButton' => 'NewOverride' ),
    "params" => array( ) );

$ViewList['templateedit'] = array(
    'script' => 'templateedit.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezvisualnavigationpart',
    'single_post_actions' => array( 'SaveButton' => 'Save',
                                    'DiscardButton' => 'Discard' ),
    'params' => array( ),
    'unordered_params' => array( 'siteAccess' => 'SiteAccess' ) );

$ViewList['templatecreate'] = array(
    'script' => 'templatecreate.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezvisualnavigationpart',
    'single_post_actions' => array( 'CreateOverrideButton' => 'CreateOverride',
                                    'CancelOverrideButton' => 'CancelOverride' ),
    'params' => array( ),
    'unordered_params' => array( 'siteAccess' => 'SiteAccess',
                                 'classID' => 'ClassID',
                                 'nodeID' => 'NodeID' ) );

?>
