<?php
//
// Created on: <11-Aug-2003 18:12:39 amos>
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


$module = $Params['Module'];
$viewMode = $Params['ViewMode'];
$packageName = $Params['PackageName'];
$repositoryID = false;
if ( isset( $Params['RepositoryID'] ) and $Params['RepositoryID'] )
    $repositoryID = $Params['RepositoryID'];

$package = eZPackage::fetch( $packageName, false, $repositoryID );
if ( !is_object( $package ) )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$package->attribute( 'can_read' ) )
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );


if ( $module->isCurrentAction( 'Export' ) )
{
    return $module->run( 'export', array( $packageName ) );
}
else if ( $module->isCurrentAction( 'Install' ) )
{
    return $module->redirectToView( 'install', array( $packageName ) );
}
else if ( $module->isCurrentAction( 'Uninstall' ) )
{
    return $module->redirectToView( 'uninstall', array( $packageName ) );
}

$repositoryInformation = $package->currentRepositoryInformation();

$tpl = eZTemplate::factory();

$tpl->setVariable( 'package_name', $packageName );
$tpl->setVariable( 'repository_id', $repositoryID );

$Result = array();
$Result['content'] = $tpl->fetch( "design:package/view/$viewMode.tpl" );
$path = array( array( 'url' => 'package/list',
                      'text' => ezpI18n::tr( 'kernel/package', 'Packages' ) ) );
if ( $repositoryInformation and $repositoryInformation['id'] != 'local' )
{
    $path[] = array( 'url' => 'package/list/' . $repositoryInformation['id'],
                     'text' => $repositoryInformation['name'] );
}
$path[] = array( 'url' => false,
                 'text' => $package->attribute( 'name' ) );
$Result['path'] = $path;

?>
