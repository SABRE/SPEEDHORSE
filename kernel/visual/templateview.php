<?php
//
// Created on: <08-May-2003 11:16:19 bf>
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
$parameters = $Params["Parameters"];


$ini = eZINI::instance();
$tpl = eZTemplate::factory();

$template = "";

foreach ( $parameters as $param )
{
    $template .= "/$param";
}

if ( $module->isCurrentAction( 'SelectCurrentSiteAccess' ) )
{
    if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    {
        $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $http->postVariable( 'CurrentSiteAccess' ) );
    }
}

// Fetch siteaccess settings for the selected override
// Default to first defined siteacces if none are selected
if ( !$http->hasSessionVariable( 'eZTemplateAdminCurrentSiteAccess' ) )
{
    $siteAccessList = $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' );
    $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $siteAccessList[0] );
}

$siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

if ( $module->isCurrentAction( 'NewOverride' ) )
{
    if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    {
        $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $http->postVariable( 'CurrentSiteAccess' ) );
    }

    $module->redirectTo( '/visual/templatecreate'. $template );
    return eZModule::HOOK_STATUS_CANCEL_RUN;
}

if ( $module->isCurrentAction( 'UpdateOverride' ) )
{
    if ( $http->hasPostVariable( 'PriorityArray' ) )
    {
        $priorityArray = $http->postVariable( 'PriorityArray' );

        // Load override.ini for the current siteaccess
        $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
        $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
        $overrideINI->loadCache();

        asort( $priorityArray );
        $currentINIGroups =& $overrideINI->groups();

        $newGroupArray = array();
        foreach ( array_keys( $priorityArray ) as $key )
        {
            $newGroupArray[$key] = $currentINIGroups[$key];
            unset( $currentINIGroups[$key] );
        }
        $overrideINI->setGroups( array_merge( $currentINIGroups, $newGroupArray ) );

        $filePermission = $ini->variable( 'FileSettings', 'StorageFilePermissions' );

        $oldumask = umask( 0 );
        $overrideINI->save( "siteaccess/$siteAccess/override.ini.append" );
        chmod( "settings/siteaccess/$siteAccess/override.ini.append", octdec( $filePermission ) );
        umask( $oldumask );
    }
}

$overrideINISaveFailed = false;
$notRemoved = array();

if ( $module->isCurrentAction( 'RemoveOverride' ) )
{
    if ( $http->hasPostVariable( 'RemoveOverrideArray' ) )
    {
        $removeOverrideArray = $http->postVariable( 'RemoveOverrideArray' );
        // TODO: read from correct site.ini
        $siteBase = $siteAccess;

        // Load override.ini for the current siteaccess
        $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
        $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
        $overrideINI->loadCache();

        $siteINI = eZINI::instance( 'site.ini', 'settings', null, null, true );
        $siteINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
        $siteINI->loadCache();
        $siteBase = $siteINI->variable( 'DesignSettings', 'SiteDesign' );

        // Remove settings and file
        foreach ( $removeOverrideArray as $removeOverride )
        {
            $group = $overrideINI->group( $removeOverride );

            $fileName = "design/$siteBase/override/templates/" . $group['MatchFile'];

            if ( unlink( $fileName ) )
            {
                $overrideINI->removeGroup( $removeOverride );
            }
            else
            {
                $notRemoved[] = array( 'filename' => $fileName );
            }
        }
        if ( $overrideINI->save( "siteaccess/$siteAccess/override.ini.append" ) == false )
        {
            $overrideINISaveFailed = true;
        }

        // Expire content view cache
        eZContentCacheManager::clearAllContentCache();

        // Clear override cache
        $cachedDir = eZSys::cacheDirectory();
        $cachedDir .= "/override/";
        eZDir::recursiveDelete( $cachedDir );
    }
}

$overrideArray = eZTemplateDesignResource::overrideArray( $siteAccess );

$templateSettings = false;
if ( isset( $overrideArray[$template] ) )
{
    $templateSettings = $overrideArray[$template];
}

if ( !isset( $templateSettings['custom_match'] ) )
    $templateSettings['custom_match'] = 0;

$tpl->setVariable( 'template_settings', $templateSettings );
$tpl->setVariable( 'current_siteaccess', $siteAccess );
$tpl->setVariable( 'not_removed', $notRemoved );
$tpl->setVariable( 'ini_not_saved', $overrideINISaveFailed );

$siteINI = eZINI::instance( 'site.ini' );
if ( $siteINI->variable( 'BackwardCompatibilitySettings', 'UsingDesignAdmin34' ) == 'enabled' )
{
    $tpl->setVariable( 'custom_match', $templateSettings['custom_match'] );
}

$Result = array();
$Result['content'] = $tpl->fetch( "design:visual/templateview.tpl" );
$Result['path'] = array( array( 'url' => "/visual/templatelist/",
                                'text' => ezpI18n::tr( 'kernel/design', 'Template list' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/design', 'Template view' ) ) );
?>
