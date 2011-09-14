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

if ( !eZPackage::canUsePolicyFunction( 'import' ) )
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

$package = false;
$installElements = false;
$errorList = array();

if ( $module->isCurrentAction( 'UploadPackage' ) )
{
    if ( eZHTTPFile::canFetch( 'PackageBinaryFile' ) )
    {
        $file = eZHTTPFile::fetch( 'PackageBinaryFile' );
        if ( $file )
        {
            $packageFilename = $file->attribute( 'filename' );

            $package = eZPackage::import( $packageFilename, $packageName );
            if ( $package instanceof eZPackage )
            {
                if ( $package->attribute( 'install_type' ) != 'install' or
                     !$package->attribute( 'can_install' ) )
                {
                    return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
                }
                else if ( $package->attribute( 'install_type' ) == 'install' )
                {
                    return $module->redirectToView( 'install', array( $package->attribute( 'name' ) ) );
                }
            }
            else if ( $package == eZPackage::STATUS_ALREADY_EXISTS )
            {
                $errorList[] = array( 'description' => ezpI18n::tr( 'kernel/package', 'Package %packagename already exists, cannot import the package', false, array( '%packagename' => $packageName ) ) );
            }
            else if ( $package == eZPackage::STATUS_INVALID_NAME )
            {
                $errorList[] = array( 'description' => ezpI18n::tr( 'kernel/package', 'The package name %packagename is invalid, cannot import the package', false, array( '%packagename' => $packageName ) ) );
            }
            else
            {
                eZDebug::writeError( "Uploaded file is not an eZ Publish package" );
            }
        }
        else
        {
            eZDebug::writeError( "Failed fetching upload package file" );
        }
    }
    else
    {
        eZDebug::writeError( "No uploaded package file was found" );
    }
}
else if ( $module->isCurrentAction( 'UploadCancel' ) )
{
    $module->redirectToView( 'list' );
    return;
}

$tpl = eZTemplate::factory();

$tpl->setVariable( 'package', $package );
$tpl->setVariable( 'error_list', $errorList );

$Result = array();
$Result['content'] = $tpl->fetch( "design:package/upload.tpl" );
$Result['path'] = array( array( 'url' => 'package/list',
                                'text' => ezpI18n::tr( 'kernel/package', 'Packages' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/package', 'Upload' ) ) );

?>
