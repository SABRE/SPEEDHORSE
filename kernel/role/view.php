<?php
//
// Created on: <22-Aug-2002 16:38:41 sp>
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

/*! \file
*/



$http = eZHTTPTool::instance();
$Module = $Params['Module'];
$roleID = $Params['RoleID'];

$role = eZRole::fetch( $roleID );

if ( !$role )
{
    $Module->redirectTo( '/role/list/' );
    return;
}

// Redirect to role edit
if ( $http->hasPostVariable( 'EditRoleButton' ) )
{
    $Module->redirectTo( '/role/edit/' . $roleID );
    return;
}

// Redirect to content node browse in the user tree
if ( $http->hasPostVariable( 'AssignRoleButton' ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'AssignRole',
                                    'from_page' => '/role/assign/' . $roleID,
                                    'cancel_page' => '/role/view/'. $roleID ),
                             $Module );

    return;
}
else if ( $http->hasPostVariable( 'AssignRoleLimitedButton' ) )
{
    $Module->redirectTo( '/role/assign/' . $roleID . '/' . $http->postVariable( 'AssignRoleType' ) );
    return;
}

// Assign the role for a user or group
if ( $Module->isCurrentAction( 'AssignRole' ) )
{
    $selectedObjectIDArray = eZContentBrowse::result( 'AssignRole' );

    $assignedUserIDArray = $role->fetchUserID();

    $db = eZDB::instance();
    $db->begin();
    foreach ( $selectedObjectIDArray as $objectID )
    {
        if ( !in_array(  $objectID, $assignedUserIDArray ) )
        {
            $role->assignToUser( $objectID );
        }
    }
    /* Clean up policy cache */
    eZUser::cleanupCache();

    // Clear role caches.
    eZRole::expireCache();

    // Clear all content cache.
    eZContentCacheManager::clearAllContentCache();

    $db->commit();
}

// Remove the role assignment
if ( $http->hasPostVariable( 'RemoveRoleAssignmentButton' ) )
{
    $idArray = $http->postVariable( "IDArray" );

    $db = eZDB::instance();
    $db->begin();
    foreach ( $idArray as $id )
    {
        $role->removeUserAssignmentByID( $id );
    }
    /* Clean up policy cache */
    eZUser::cleanupCache();

    // Clear role caches.
    eZRole::expireCache();

    // Clear all content cache.
    eZContentCacheManager::clearAllContentCache();

    $db->commit();
}

$tpl = eZTemplate::factory();

$userArray = $role->fetchUserByRole();

$policies = $role->attribute( 'policies' );
$tpl->setVariable( 'policies', $policies );
$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'role', $role );

$tpl->setVariable( 'user_array', $userArray );

$Module->setTitle( 'View role - ' . $role->attribute( 'name' ) );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:role/view.tpl' );
$Result['path'] = array( array( 'text' => 'Role',
                                'url' => 'role/list' ),
                         array( 'text' => $role->attribute( 'name' ),
                                'url' => false ) );

?>
