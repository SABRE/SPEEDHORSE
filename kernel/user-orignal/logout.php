<?php
//
// Created on: <30-Apr-2002 12:41:17 bf>
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

$http = eZHTTPTool::instance();

$user = eZUser::instance();

// Remove all temporary drafts
eZContentObject::cleanupAllInternalDrafts( $user->attribute( 'contentobject_id' ) );

$user->logoutCurrent();

$http->setSessionVariable( 'force_logout', 1 );

$ini = eZINI::instance();
if ( $ini->variable( 'UserSettings', 'RedirectOnLogoutWithLastAccessURI' ) == 'enabled' && $http->hasSessionVariable( 'LastAccessesURI' ))
{
    $redirectURL = $http->sessionVariable( "LastAccessesURI" );
}
else
{
    $redirectURL = $http->postVariable( 'RedirectURI', $ini->variable( 'UserSettings', 'LogoutRedirect' ) );
}

return $Module->redirectTo( $redirectURL );

?>
