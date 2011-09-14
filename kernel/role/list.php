<?php
//
// Created on: <15-Aug-2002 14:37:29 bf>
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


$Module = $Params['Module'];

$offset = $Params['Offset'];

if( eZPreferences::value( 'admin_role_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_role_list_limit' ) )
    {
        case '2': { $limit = 25; } break;
        case '3': { $limit = 50; } break;
        default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
   if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        $db = eZDB::instance();
        $db->begin();
        foreach ( $deleteIDArray as $deleteID )
        {
            eZRole::removeRole( $deleteID );
        }
        // Clear role caches.
        eZRole::expireCache();

        // Clear all content cache.
        eZContentCacheManager::clearAllContentCache();

        $db->commit();
    }
}
// Redirect to content node browse in the user tree
// Assign the role for a user or group
if ( $Module->isCurrentAction( 'AssignRole' ) )
{
    $selectedObjectIDArray = eZContentBrowse::result( 'AssignRole' );

    foreach ( $selectedObjectIDArray as $objectID )
    {
        $role->assignToUser( $objectID );
    }
    // Clear role caches.
    eZRole::expireCache();

    // Clear all content cache.
    eZContentCacheManager::clearAllContentCache();
}

if ( $http->hasPostVariable( 'NewButton' )  )
{
    $role = eZRole::createNew( );
    return $Module->redirectToView( 'edit', array( $role->attribute( 'id' ) ) );
}

$viewParameters = array( 'offset' => $offset );
$tpl = eZTemplate::factory();

$roles = eZRole::fetchByOffset( $offset, $limit, $asObject = true, $ignoreTemp = true );
$roleCount = eZRole::roleCount();
$tempRoles = eZRole::fetchList( $temporaryVersions = true );
$tpl->setVariable( 'roles', $roles );
$tpl->setVariable( 'role_count', $roleCount );
$tpl->setVariable( 'temp_roles', $tempRoles );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'limit', $limit );


$Result = array();
$Result['content'] = $tpl->fetch( 'design:role/list.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/role', 'Role list' ) ) );
?>
