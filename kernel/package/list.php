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
$offset = (int)$Params['Offset'];

$repositoryID = 'local';
if ( $Params['RepositoryID'] )
    $repositoryID = $Params['RepositoryID'];

if ( $module->isCurrentAction( 'InstallPackage' ) )
{
    return $module->redirectToView( 'upload' );
}

$removeList = array();
if ( $module->isCurrentAction( 'RemovePackage' ) or
     $module->isCurrentAction( 'ConfirmRemovePackage' ) )
{
    if ( $module->hasActionParameter( 'PackageSelection' ) )
    {
        $removeConfirmation = $module->isCurrentAction( 'ConfirmRemovePackage' );
        $packageSelection = $module->actionParameter( 'PackageSelection' );
        foreach ( $packageSelection as $packageID )
        {
            $package = eZPackage::fetch( $packageID );
            if ( $package )
            {
                if ( $removeConfirmation )
                {
                    $package->remove();
                }
                else
                {
                    $removeList[] = $package;
                }
            }
        }
        if ( $removeConfirmation )
            return $module->redirectToView( 'list' );
    }
}

if ( $module->isCurrentAction( 'ChangeRepository' ) )
{
    $repositoryID = $module->actionParameter( 'RepositoryID' );
}

if ( $module->isCurrentAction( 'CreatePackage' ) )
{
    return $module->redirectToView( 'create' );
}

$tpl = eZTemplate::factory();

$viewParameters = array( 'offset' => $offset );

$tpl->setVariable( 'module_action', $module->currentAction() );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'remove_list', $removeList );
$tpl->setVariable( 'repository_id', $repositoryID );

$Result = array();
$Result['content'] = $tpl->fetch( "design:package/list.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/package', 'Packages' ) ) );

?>
