<?php
//
// Created on: <13-Feb-2005 03:13:00 bh>
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

$Module = array( 'name' => 'eZInfoCollector' );

$ViewList = array();
$ViewList['overview'] = array(
    'script' => 'overview.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'ui_context' => 'view',
    'unordered_params' => array( 'offset' => 'Offset' ),
    'single_post_actions' => array( 'RemoveObjectCollectionButton' => 'RemoveObjectCollection',
                                    'ConfirmRemoveButton' => 'ConfirmRemoval',
                                    'CancelRemoveButton' => 'CancelRemoval' ) );

$ViewList['collectionlist'] = array(
    'script' => 'collectionlist.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'ui_context' => 'view',
    'params' => array( 'ObjectID' ),
    'unordered_params' => array( 'offset' => 'Offset' ),
    'single_post_actions' => array( 'RemoveCollectionsButton' => 'RemoveCollections',
                                    'ConfirmRemoveButton' => 'ConfirmRemoval',
                                    'CancelRemoveButton' => 'CancelRemoval' ) );

$ViewList['view'] = array(
    'script' => 'view.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'ui_context' => 'view',
    'params' => array( 'CollectionID' ) );


$FunctionList = array();
$FunctionList['read'] = array();

?>
