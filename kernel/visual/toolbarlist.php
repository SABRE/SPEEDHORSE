<?php
//
// Definition of Toolbarlist class
//
// Created on: <05-Mar-2004 13:05:16 wy>
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

$currentSiteAccess = false;
if ( $http->hasSessionVariable( 'eZTemplateAdminCurrentSiteAccess' ) )
    $currentSiteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

$module = $Params['Module'];
if ( $Params['SiteAccess'] )
    $currentSiteAccess = $Params['SiteAccess'];

$ini = eZINI::instance();
$siteAccessList = $ini->variable( 'SiteAccessSettings', 'RelatedSiteAccessList' );

if ( $http->hasPostVariable( 'CurrentSiteAccess' ) )
    $currentSiteAccess = $http->postVariable( 'CurrentSiteAccess' );

if ( !in_array( $currentSiteAccess, $siteAccessList ) )
    $currentSiteAccess = $siteAccessList[0];

if ( $http->hasPostVariable( 'SelectCurrentSiteAccessButton' ) )
{
    $http->setSessionVariable( 'eZTemplateAdminCurrentSiteAccess', $currentSiteAccess );
}

$toolbarIni = eZINI::instance( "toolbar.ini", null, null, null, true );
$toolbarIni->prependOverrideDir( "siteaccess/$currentSiteAccess", false, 'siteaccess' );
$toolbarIni->loadCache();

if ( $toolbarIni->hasVariable( "Toolbar", "AvailableToolBarArray" ) )
{
    $toolbarArray =  $toolbarIni->variable( "Toolbar", "AvailableToolBarArray" );
}
$tpl = eZTemplate::factory();

$tpl->setVariable( 'toolbar_list', $toolbarArray );
$tpl->setVariable( 'siteaccess_list', $siteAccessList );
$tpl->setVariable( 'current_siteaccess', $currentSiteAccess );

$Result = array();
$Result['content'] = $tpl->fetch( "design:visual/toolbarlist.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'design/standard/toolbar', 'Toolbar management' ) ) );


?>
