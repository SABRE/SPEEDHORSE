<?php
//
// Created on: <30-Apr-2002 12:36:36 bf>
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

$Module = array( 'name' => 'User management',
                 'variable_params' => true );

$ViewList = array();
$ViewList['logout'] = array(
    'functions' => array( 'login' ),
    'script' => 'logout.php',
    'ui_context' => 'authentication',
    'params' => array( ) );

$ViewList['login'] = array(
    'functions' => array( 'login' ),
    'script' => 'login.php',
    'ui_context' => 'authentication',
    'default_action' => array( array( 'name' => 'Login',
                                      'type' => 'post',
                                      'parameters' => array( 'Login',
                                                             'Password' ) ) ),
    'single_post_actions' => array( 'LoginButton' => 'Login' ),
    'post_action_parameters' => array( 'Login' => array( 'UserLogin' => 'Login',
                                                         'UserPassword' => 'Password',
                                                         'UserRedirectURI' => 'RedirectURI' ) ),
    'params' => array( ) );

$ViewList['setting'] = array(
    'functions' => array( 'preferences' ),
    'default_navigation_part' => 'ezusernavigationpart',
    'script' => 'setting.php',
    'params' => array( 'UserID' ) );

$ViewList['preferences'] = array(
    'functions' => array( 'login' ),
    'script' => 'preferences.php',
    'params' => array( 'Function', 'Key', 'Value' ) );

$ViewList['password'] = array(
    'functions' => array( 'password' ),
    'script' => 'password.php',
    'ui_context' => 'administration',
    'default_navigation_part' => 'ezmynavigationpart',
    'params' => array( 'UserID' ) );

/// \deprecated This view is kept for compatibility
$ViewList['forgetpassword'] = array(
    'functions' => array( 'password' ),
    'script' => 'forgotpassword.php',
    'deprecated' => true,
    'params' => array( ),
    'ui_context' => 'administration',
    'ui_component' => 'forgotpassword',
    'single_post_actions' => array( 'GenerateButton' => 'Generate' ),
    'post_action_parameters' => array( 'Generate' => array( 'Login' => 'UserLogin',
                                                            'Email' => 'UserEmail' ) ),
    'params' => array( 'HashKey' ) );

/// Note the function above is misspelled and should be removed
$ViewList['forgotpassword'] = array(
    'functions' => array( 'password' ),
    'script' => 'forgotpassword.php',
    'params' => array( ),
    'ui_context' => 'administration',
    'single_post_actions' => array( 'GenerateButton' => 'Generate' ),
    'post_action_parameters' => array( 'Generate' => array( 'Login' => 'UserLogin',
                                                            'Email' => 'UserEmail' ) ),
    'params' => array( 'HashKey' ) );

/// \deprecated Use normal content edit view instead
$ViewList['edit'] = array(
    'functions' => array( 'login' ),
    'script' => 'edit.php',
    'ui_context' => 'edit',
    'single_post_actions' => array( 'ChangePasswordButton' => 'ChangePassword',
                                    'ChangeSettingButton' => 'ChangeSetting',
                                    'CancelButton' => 'Cancel',
                                    'EditButton' => 'Edit' ),
    'params' => array( 'UserID' ) );

$ViewList['register'] = array(
    'functions' => array( 'register' ),
    'script' => 'register.php',
    'params' => array( 'redirect_number' ),
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezmynavigationpart',
    'single_post_actions' => array( 'PublishButton' => 'Publish',
                                    'CancelButton' => 'Cancel',
                                    'CustomActionButton' => 'CustomAction' ) );

$ViewList['activate'] = array(
    'functions' => array( 'login' ),
    'script' => 'activate.php',
    'ui_context' => 'authentication',
    'default_navigation_part' => 'ezmynavigationpart',
    'params' => array( 'Hash', 'MainNodeID' ) );

$ViewList['success'] = array(
    'functions' => array( 'register' ),
    'script' => 'success.php',
    'ui_context' => 'authentication',
    'default_navigation_part' => 'ezmynavigationpart',
    'params' => array( ) );


$SiteAccess = array(
    'name'=> 'SiteAccess',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezsiteaccess.php',
    'class' => 'eZSiteAccess',
    'function' => 'siteAccessList',
    'parameter' => array()
    );

$FunctionList = array();
$FunctionList['login'] = array( 'SiteAccess' => $SiteAccess );
$FunctionList['password'] = array();
$FunctionList['preferences'] = array();
$FunctionList['register'] = array();
$FunctionList['selfedit'] = array();

?>
