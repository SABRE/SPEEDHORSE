<?php
/**
 * File containing the userpaex module definition
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @package ezmbpaex
 */

$Module = array( 'name' => 'User with Password Expiry management',
                 'variable_params' => true );

$ViewList = array();

$ViewList['password'] = array(
    'functions' => array( 'password' ),
    'script' => 'password.php',
    'ui_context' => 'administration',
    'default_navigation_part' => 'ezmynavigationpart',
    'params' => array( 'UserID' ) );

$ViewList['forgotpassword'] = array(
    'functions' => array( 'password' ),
    'script' => 'forgotpassword.php',
    'params' => array( ),
    'ui_context' => 'administration',
    'single_post_actions' => array( 'GenerateButton' => 'Generate',
                                    'ChangePasswdButton' => 'ChangePassword' ),
    'post_action_parameters' => array( 'Generate' => array( 'Login' => 'UserLogin',
                                                            'Email' => 'UserEmail' ),
                                       'ChangePassword' => array('NewPassword' => 'NewPassword',
                                                                 'NewPasswordConfirm' => 'NewPasswordConfirm' ) ),
    'params' => array( 'HashKey' ) );

$FunctionList = array();
$FunctionList['password'] = array();
$FunctionList['editpaex'] = array();

?>
