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

$Module = array( 'name' => 'eZCollaboration' );

$ViewList = array();
$ViewList['action'] = array(
    'script' => 'action.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'default_action' => array( array( 'name' => 'Custom',
                                      'type' => 'post',
                                      'parameters' => array( 'CollaborationActionCustom',
                                                             'CollaborationTypeIdentifier',
                                                             'CollaborationItemID' ) ) ),
    'post_action_parameters' => array( 'Custom' => array( 'TypeIdentifer' => 'CollaborationTypeIdentifier',
                                                          'ItemID' => 'CollaborationItemID' ) ),
    'params' => array() );
$ViewList['view'] = array(
    'script' => 'view.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'params' => array( 'ViewMode' ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" ) );
$ViewList['item'] = array(
    'script' => 'item.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'params' => array( 'ViewMode', 'ItemID' ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" ) );
$ViewList['group'] = array(
    'script' => 'group.php',
    "default_navigation_part" => 'ezmynavigationpart',
    'params' => array( 'ViewMode', 'GroupID' ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" ) );

?>
