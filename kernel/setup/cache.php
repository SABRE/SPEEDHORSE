<?php
//
// Created on: <15-Apr-2003 11:25:31 bf>
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


$ini = eZINI::instance( );
$tpl = eZTemplate::factory();

$cacheList = eZCache::fetchList();

$cacheCleared = array( 'all' => false,
                       'content' => false,
                       'ini' => false,
                       'template' => false,
                       'list' => false,
                       'static' => false );

$contentCacheEnabled = $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled';
$iniCacheEnabled = true;
$templateCacheEnabled = $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled';

$cacheEnabledList = array();
foreach ( $cacheList as $cacheItem )
{
    $cacheEnabledList[$cacheItem['id']] = $cacheItem['enabled'];
}

$cacheEnabled = array( 'all' => true,
                       'content' => $contentCacheEnabled,
                       'ini' => $iniCacheEnabled,
                       'template' => $templateCacheEnabled,
                       'list' => $cacheEnabledList );

if ( $module->isCurrentAction( 'ClearAllCache' ) )
{
    eZCache::clearAll();
    $cacheCleared['all'] = true;
}

if ( $module->isCurrentAction( 'ClearContentCache' ) )
{
    eZCache::clearByTag( 'content' );
    $cacheCleared['content'] = true;
}

if ( $module->isCurrentAction( 'ClearINICache' ) )
{
    eZCache::clearByTag( 'ini' );
    $cacheCleared['ini'] = true;
}

if ( $module->isCurrentAction( 'ClearTemplateCache' ) )
{
    eZCache::clearByTag( 'template' );
    $cacheCleared['template'] = true;
}

if ( $module->isCurrentAction( 'ClearCache' ) && $module->hasActionParameter( 'CacheList' ) && is_array( $module->actionParameter( 'CacheList' ) ) )
{
    $cacheClearList = $module->actionParameter( 'CacheList' );
    eZCache::clearByID( $cacheClearList );
    $cacheItemList = array();
    foreach ( $cacheClearList as $cacheClearItem )
    {
        foreach ( $cacheList as $cacheItem )
        {
            if ( $cacheItem['id'] == $cacheClearItem )
            {
                $cacheItemList[] = $cacheItem;
                break;
            }
        }
    }
    $cacheCleared['list'] = $cacheItemList;
}

if ( $module->isCurrentAction( 'RegenerateStaticCache' ) )
{
    $staticCache = new eZStaticCache();
    $staticCache->generateCache( true, true );
    eZStaticCache::executeActions();
    $cacheCleared['static'] = true;
}

$tpl->setVariable( "cache_cleared", $cacheCleared );
$tpl->setVariable( "cache_enabled", $cacheEnabled );
$tpl->setVariable( 'cache_list', $cacheList );


$Result = array();
$Result['content'] = $tpl->fetch( "design:setup/cache.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/setup', 'Cache admin' ) ) );

?>
