<?php
//
// Created on: <21-Feb-2005 16:53:31 ks>
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
$module = $Params['Module'];

$cacheType = $module->actionParameter( 'CacheType' );

eZPreferences::setValue( 'admin_clearcache_type', $cacheType );

if ( $module->hasActionParameter ( 'NodeID' ) )
    $nodeID = $module->actionParameter( 'NodeID' );

if ( $module->hasActionParameter ( 'ObjectID' ) )
    $objectID = $module->actionParameter( 'ObjectID' );

if ( $cacheType == 'All' )
{
    eZCache::clearAll();
}
elseif ( $cacheType == 'Template' )
{
    eZCache::clearByTag( 'template' );
}
elseif ( $cacheType == 'Content' )
{
    eZCache::clearByTag( 'content' );
}
elseif ( $cacheType == 'TemplateContent' )
{
    eZCache::clearByTag( 'template' );
    eZCache::clearByTag( 'content' );
}
elseif ( $cacheType == 'Ini' )
{
    eZCache::clearByTag( 'ini' );
}
elseif ( $cacheType == 'Static' )
{
    $staticCache = new eZStaticCache();
    $staticCache->generateCache( true, true );
    $cacheCleared['static'] = true;
}
elseif ( $cacheType == 'ContentNode' )
{
    $contentModule = eZModule::exists( 'content' );
    if ( $contentModule instanceof eZModule )
    {
        $contentModule->setCurrentAction( 'ClearViewCache', 'action' );

        $contentModule->setActionParameter( 'NodeID', $nodeID, 'action' );
        $contentModule->setActionParameter( 'ObjectID', $objectID, 'action' );

        $contentModule->run( 'action', array( $nodeID, $objectID) );
    }
}
elseif ( $cacheType == 'ContentSubtree' )
{
    $contentModule = eZModule::exists( 'content' );
    if ( $contentModule instanceof eZModule )
    {
        $contentModule->setCurrentAction( 'ClearViewCacheSubtree', 'action' );

        $contentModule->setActionParameter( 'NodeID', $nodeID, 'action' );
        $contentModule->setActionParameter( 'ObjectID', $objectID, 'action' );

        $contentModule->run( 'action', array( $nodeID, $objectID) );
    }
}

$uri = $http->postVariable( 'RedirectURI', $http->sessionVariable( 'LastAccessedModifyingURI', '/' ) );
$module->redirectTo( $uri );

?>
