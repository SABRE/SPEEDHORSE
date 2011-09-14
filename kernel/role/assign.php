<?php
//
//
// Created on: <16-���-2002 10:45:47 sp>
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
$roleID = $Params['RoleID'];
$limitIdent = $Params['LimitIdent'];
$limitValue = $Params['LimitValue'];

if ( $http->hasPostVariable( 'AssignSectionCancelButton' ) )
{
    $Module->redirectTo( '/role/view/' . $roleID );
}

if ( $http->hasPostVariable( 'BrowseCancelButton' ) )
{
    if ( $http->hasPostVariable( 'BrowseCancelURI' ) )
    {
        return $Module->redirectTo( $http->postVariable( 'BrowseCancelURI' ) );
    }
}

if ( $http->hasPostVariable( 'AssignSectionID' ) &&
     $http->hasPostVariable( 'SectionID' ) )
{
    $Module->redirectTo( '/role/assign/' . $roleID . '/' . $limitIdent . '/' . $http->postVariable( 'SectionID' ) );
}
else if ( $http->hasPostVariable( 'BrowseActionName' ) and
          $http->postVariable( 'BrowseActionName' ) == 'SelectObjectRelationNode' )
{
    $selectedNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
    if ( count( $selectedNodeIDArray ) == 1 )
    {
        $limitValue = $selectedNodeIDArray[0];
    }
    $Module->redirectTo( '/role/assign/' . $roleID . '/' . $limitIdent . '/' . $limitValue );
}
else if ( $http->hasPostVariable( 'BrowseActionName' ) and
          $http->postVariable( 'BrowseActionName' ) == 'AssignRole' )
{
    $selectedObjectIDArray = $http->postVariable( 'SelectedObjectIDArray' );
    $role = eZRole::fetch( $roleID );

    $db = eZDB::instance();
    $db->begin();
    foreach ( $selectedObjectIDArray as $objectID )
    {
        $role->assignToUser( $objectID, $limitIdent, $limitValue );
    }
    // Clear role caches.
    eZRole::expireCache();

    $db->commit();
    if ( count( $selectedObjectIDArray ) > 0 )
    {
        eZContentCacheManager::clearAllContentCache();
    }

    /* Clean up policy cache */
    eZUser::cleanupCache();

    $Module->redirectTo( '/role/view/' . $roleID );
}
else if ( is_string( $limitIdent ) && !isset( $limitValue ) )
{
    switch( $limitIdent )
    {
        case 'subtree':
        {
            eZContentBrowse::browse( array( 'action_name' => 'SelectObjectRelationNode',
                                            'from_page' => '/role/assign/' . $roleID . '/' . $limitIdent,
                                            'cancel_page' => '/role/view/' . $roleID ),
                                     $Module );
            return;
        } break;

        case 'section':
        {
            $sectionArray = eZSection::fetchList( );
            $tpl = eZTemplate::factory();
            $tpl->setVariable( 'section_array', $sectionArray );
            $tpl->setVariable( 'role_id', $roleID );
            $tpl->setVariable( 'limit_ident', $limitIdent );

            $Result = array();
            $Result['content'] = $tpl->fetch( 'design:role/assign_limited_section.tpl' );
            $Result['path'] = array( array( 'url' => false,
                                            'text' => ezpI18n::tr( 'kernel/role', 'Limit on section' ) ) );
            return;
        } break;

        default:
        {
            eZDebug::writeWarning( 'Unsupported assign limitation: ' . $limitIdent );
            $Module->redirectTo( '/role/view/' . $roleID );
        } break;
    }
}
else if ( is_numeric( $roleID ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'AssignRole',
                                    'from_page' => '/role/assign/' . $roleID . '/' . $limitIdent . '/' . $limitValue,
                                    'cancel_page' => '/role/view/' . $roleID ),
                             $Module );

    return;
}

?>
