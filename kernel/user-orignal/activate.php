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
$http = eZHTTPTool::instance();

$hash = trim( $http->hasPostVariable( 'Hash' ) ? $http->postVariable( 'Hash' ) : $Params['Hash'] );
$mainNodeID = (int) $http->hasPostVariable( 'MainNodeID' ) ? $http->postVariable( 'MainNodeID' ) : $Params['MainNodeID'];

// Prepend or append the hash string with a salt, and md5 the resulting hash
// Example: use is login name as salt, and a 'secret password' as hash sent to the user
if ( $http->hasPostVariable( 'HashSaltPrepend' ) )
    $hash =  md5( trim( $http->postVariable( 'HashSaltPrepend' ) ) . $hash );
else if ( $http->hasPostVariable( 'HashSaltAppend' ) )
    $hash =  md5( $hash . trim( $http->postVariable( 'HashSaltAppend' ) ) );


// Check if key exists
$accountActivated = false;
$alreadyActive = false;
$isPending = false;
$accountKey = $hash ? eZUserAccountKey::fetchByKey( $hash ) : false;

if ( $accountKey )
{
    $accountActivated = true;
    $userID = $accountKey->attribute( 'user_id' );

    $userContentObject = eZContentObject::fetch( $userID );
    if ( !$userContentObject instanceof eZContentObject )
    {
        return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
    }

    if ( $userContentObject->attribute('main_node_id') != $mainNodeID )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    // Enable user account
    if ( eZOperationHandler::operationIsAvailable( 'user_activation' ) )
    {
        $operationResult = eZOperationHandler::execute( 'user',
                                                        'activation', array( 'user_id'    => $userID,
                                                                             'user_hash'  => $hash,
                                                                             'is_enabled' => true ) );
    }
    else
    {
        eZUserOperationCollection::activation( $userID, $hash, true );
    }

    // execute operation to publish the user object
    $publishResult = eZOperationHandler::execute( 'user' , 'register', array( 'user_id'=> $userID ) );
    if( $publishResult['status'] === eZModuleOperationInfo::STATUS_HALTED )
    {
        $isPending = true;
    }
    else
    {
        // Log in user
        $user = eZUser::fetch( $userID );

        if ( $user === null )
            return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );

        $user->loginCurrent();
    }
}
elseif( $mainNodeID )
{
    $userContentObject = eZContentObject::fetchByNodeID( $mainNodeID );
    if ( $userContentObject instanceof eZContentObject )
    {
        $userSetting = eZUserSetting::fetch( $userContentObject->attribute( 'id' ) );

        if ( $userSetting !== null && $userSetting->attribute( 'is_enabled' ) )
        {
            $alreadyActive = true;
        }
    }
}

// Template handling

$tpl = eZTemplate::factory();

$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'account_activated', $accountActivated );
$tpl->setVariable( 'already_active', $alreadyActive );
$tpl->setVariable( 'is_pending' , $isPending );

// This line is deprecated, the correct name of the variable should
// be 'account_activated' as shown above.
// However it is kept for backwards compatibility.
$tpl->setVariable( 'account_avtivated', $accountActivated );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:user/activate.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/user', 'User' ),
                                'url' => false ),
                         array( 'text' => ezpI18n::tr( 'kernel/user', 'Activate' ),
                                'url' => false ) );
$ini = eZINI::instance();
if ( $ini->variable( 'SiteSettings', 'LoginPage' ) == 'custom' )
    $Result['pagelayout'] = 'loginpagelayout.tpl';

?>
