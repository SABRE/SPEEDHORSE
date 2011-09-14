<?php
//
// Created on: <01-Aug-2002 09:58:09 bf>
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
if ( isset( $Params["UserID"] ) )
    $UserID = $Params["UserID"];

$http = eZHTTPTool::instance();

$user = eZUser::fetch( $UserID );
if ( !$user )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
$userObject = $user->attribute( 'contentobject' );
if ( !$userObject )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
$userSetting = eZUserSetting::fetch( $UserID );

if ( $http->hasPostVariable( "UpdateSettingButton" ) )
{
    $isEnabled = 0;
    if ( $http->hasPostVariable( 'max_login' ) )
    {
        $maxLogin = $http->postVariable( 'max_login' );
    }
    else
    {
        $maxLogin = $userSetting->attribute( 'max_login' );
    }
    if ( $http->hasPostVariable( 'is_enabled' ) )
    {
        $isEnabled = 1;
    }

    if ( eZOperationHandler::operationIsAvailable( 'user_setsettings' ) )
    {
           $operationResult = eZOperationHandler::execute( 'user',
                                                           'setsettings', array( 'user_id'    => $UserID,
                                                                                  'is_enabled' => $isEnabled,
                                                                                  'max_login'  => $maxLogin ) );
    }
    else
    {
        eZUserOperationCollection::setSettings( $UserID, $isEnabled, $maxLogin );
    }

    $Module->redirectTo( '/content/view/full/' . $userObject->attribute( 'main_node_id' ) );
    return;
}

if ( $http->hasPostVariable( "CancelSettingButton" ) )
{
    $Module->redirectTo( '/content/view/full/' . $userObject->attribute( 'main_node_id' ) );
    return;
}

if ( $http->hasPostVariable( "ResetFailedLoginButton" ) )
{
    // Reset number of failed login attempts
    eZUser::setFailedLoginAttempts( $UserID, 0, true );
}

$failedLoginAttempts = $user->failedLoginAttempts();
$maxFailedLoginAttempts = eZUser::maxNumberOfFailedLogin();

$Module->setTitle( "Edit user settings" );
// Template handling

$tpl = eZTemplate::factory();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "userID", $UserID );
$tpl->setVariable( "user", $user );
$tpl->setVariable( "userSetting", $userSetting );
$tpl->setVariable( "failed_login_attempts", $failedLoginAttempts );
$tpl->setVariable( "max_failed_login_attempts", $maxFailedLoginAttempts );

$Result = array();
$Result['content'] = $tpl->fetch( "design:user/setting.tpl" );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/user', 'User' ),
                                'url' => false ),
                         array( 'text' => ezpI18n::tr( 'kernel/user', 'Setting' ),
                                'url' => false ) );

?>
