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

$Module = array( "name" => "eZContentClass" );

$ViewList = array();
$ViewList["edit"] = array(
    "script" => "edit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "ClassID", "GroupID", "GroupName" ),
    'unordered_params' => array( 'language' => 'Language' )
    );
$ViewList["view"] = array(
    "script" => "view.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "ClassID" ),
    'unordered_params' => array( 'language' => 'Language',
                                 'scriptid' => 'ScheduledScriptID' ) );
$ViewList["copy"] = array(
    "script" => "copy.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "ClassID" ) );
$ViewList["down"] = array(
    "script" => "edit.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "ClassID", "AttributeID" ) );
$ViewList["up"] = array(
    "script" => "edit.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "ClassID", "AttributeID" ) );
$ViewList["removeclass"] = array(
    "script" => "removeclass.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "GroupID" ) );
$ViewList["removegroup"] = array(
    "script" => "removegroup.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array() );
$ViewList["classlist"] = array(
    "script" => "classlist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "GroupID" ) );
$ViewList["grouplist"] = array(
    "script" => "grouplist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array() );
$ViewList["groupedit"] = array(
    "script" => "groupedit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "GroupID" ) );
$ViewList['translation'] = array(
    'script' => 'translation.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'ui_context' => 'edit',
    'params' => array(  ),
    'single_post_actions' => array( 'CancelButton' => 'Cancel',
                                    'UpdateInitialLanguageButton' => 'UpdateInitialLanguage',
                                    'RemoveTranslationButton' => 'RemoveTranslation' ),
    'post_action_parameters' => array( 'Cancel' => array( 'ClassID' => 'ContentClassID',
                                                          'LanguageCode' => 'ContentClassLanguageCode' ),
                                       'UpdateInitialLanguage' => array( 'ClassID' => 'ContentClassID',
                                                                         'LanguageCode' => 'ContentClassLanguageCode',
                                                                         'InitialLanguageID' => 'InitialLanguageID' ),
                                       'RemoveTranslation' => array( 'ClassID' => 'ContentClassID',
                                                                     'LanguageCode' => 'ContentClassLanguageCode',
                                                                     'LanguageID' => 'LanguageID',
                                                                     'ConfirmRemoval' => 'ConfirmRemoval' ) ) );

?>
