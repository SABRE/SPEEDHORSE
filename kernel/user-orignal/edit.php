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

if ( isset( $Params['UserID'] ) && is_numeric( $Params['UserID'] ) )
{
    $UserID = $Params['UserID'];
}
else
{
    $currentUser = eZUser::currentUser();
    $UserID      = $currentUser->attribute( 'contentobject_id' );
    if ( $currentUser->isAnonymous() )
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}

if ( $Module->isCurrentAction( "ChangePassword" ) )
{
    return $Module->redirectTo( "user/password/" . $UserID  );
}

if ( $Module->isCurrentAction( "ChangeSetting" ) )
{
    return $Module->redirectTo( "user/setting/" . $UserID );
}

if ( $Module->isCurrentAction( "Cancel" ) )
{
    return $Module->redirectTo( '/content/view/sitemap/5/' );
}

$http = eZHTTPTool::instance();

if ( $Module->isCurrentAction( "Edit" ) || ( isset( $UserParameters['action'] ) && $UserParameters['action'] === 'edit' ) )
{
    $selectedVersion = $http->hasPostVariable( 'SelectedVersion' ) ? $http->postVariable( 'SelectedVersion' ) : 'f';
    $editLanguage = $http->hasPostVariable( 'ContentObjectLanguageCode' ) ? $http->postVariable( 'ContentObjectLanguageCode' ) : '';
    return $Module->redirectTo( '/content/edit/' . $UserID . '/' . $selectedVersion . '/' . $editLanguage );
}

$userAccount = eZUser::fetch( $UserID );
if ( !$userAccount )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$userObject = $userAccount->attribute( 'contentobject' );
if ( !$userObject instanceof eZContentObject  )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( !$userObject->canEdit( ) )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}


$tpl = eZTemplate::factory();
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "http", $http );
$tpl->setVariable( "userID", $UserID );
$tpl->setVariable( "userAccount", $userAccount );
$tpl->setVariable( 'view_parameters', $UserParameters );
$tpl->setVariable( 'site_access', $GLOBALS['eZCurrentAccess'] );

$Result = array();
$Result['content'] = $tpl->fetch( "design:user/edit.tpl" );
$Result['path'] = array( array( 'text' =>  ezpI18n::tr( 'kernel/user', 'User profile' ),
                                'url' => false ) );


?>
