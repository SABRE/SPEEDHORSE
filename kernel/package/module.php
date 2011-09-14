<?php
//
// Created on: <11-Aug-2003 18:11:32 amos>
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

$Module = array( 'name' => 'eZPackage' );

$ViewList = array();
$ViewList['list'] = array(
    'functions' => array( 'list' ),
    'script' => 'list.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'ChangeRepositoryButton' => 'ChangeRepository',
                                    'InstallPackageButton' => 'InstallPackage',
                                    'RemovePackageButton' => 'RemovePackage',
                                    'ConfirmRemovePackageButton' => 'ConfirmRemovePackage',
                                    'CancelRemovePackageButton' => 'CancelRemovePackage',
                                    'CreatePackageButton' => 'CreatePackage' ),
    'post_action_parameters' => array( 'ChangeRepository' => array( 'RepositoryID' => 'RepositoryID' ),
                                       'RemovePackage' => array( 'PackageSelection' => 'PackageSelection' ),
                                       'ConfirmRemovePackage' => array( 'PackageSelection' => 'PackageSelection' ) ),
    "unordered_params" => array( "offset" => "Offset" ),
    'params' => array( 'RepositoryID' ) );

$ViewList['upload'] = array(
    'functions' => array( 'import' ),
    'script' => 'upload.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'UploadPackageButton' => 'UploadPackage',
                                    'UploadCancelButton' => 'UploadCancel' ),
    'params' => array() );

$ViewList['create'] = array(
    'functions' => array( 'create' ),
    'script' => 'create.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'CreatePackageButton' => 'CreatePackage',
                                    'PackageStep' => 'PackageStep' ),
    'post_action_parameters' => array( 'CreatePackage' => array( 'CreatorItemID' => 'CreatorItemID' ),
                                       'PackageStep' => array( 'CreatorItemID' => 'CreatorItemID',
                                                               'CreatorStepID' => 'CreatorStepID',
                                                               'PreviousStep' => 'PreviousStepButton',
                                                               'NextStep' => 'NextStepButton' ) ),
    'params' => array() );

$ViewList['export'] = array(
    'functions' => array( 'export' ),
    'script' => 'export.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( 'PackageName' ) );

$ViewList['view'] = array(
    'functions' => array( 'read' ),
    'script' => 'view.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'InstallButton' => 'Install',
                                    'UninstallButton' => 'Uninstall',
                                    'ExportButton' => 'Export' ),
    'params' => array( 'ViewMode', 'PackageName', 'RepositoryID' ) );

$ViewList['install'] = array(
    'functions' => array( 'install' ),
    'script' => 'install.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'HandleError' => 'HandleError',
                                    'InstallPackageButton' => 'InstallPackage',
                                    'PackageStep' => 'PackageStep',
                                    'SkipPackageButton' => 'SkipPackage' ),
    'post_action_parameters' => array( 'InstallPackage' => array( 'InstallerType' => 'InstallerType' ),
                                       'PackageStep' => array( 'InstallerType' => 'InstallerType',
                                                               'InstallStepID' => 'InstallStepID',
                                                               'PreviousStep' => 'PreviousStepButton',
                                                               'NextStep' => 'NextStepButton' ),
                                       'HandleError' => array( 'ActionID' => 'ActionID',
                                                               'RememberAction' => 'RememberAction' ) ),

    'params' => array( 'PackageName' ) );

$ViewList['uninstall'] = array(
    'functions' => array( 'install' ),
    'script' => 'uninstall.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'HandleError' => 'HandleError',
                                    'UninstallPackageButton' => 'UninstallPackage',
                                    'SkipPackageButton' => 'SkipPackage' ),
    'post_action_parameters' => array( 'HandleError' => array( 'ActionID' => 'ActionID',
                                                               'RememberAction' => 'RememberAction' ) ),
    'params' => array( 'PackageName' ) );

$TypeID = array(
    'name'=> 'Type',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezpackage.php',
    'class' => 'eZPackage',
    'function' => 'typeList',
    'parameter' => array(  false )
    );

$CreatorTypeID = array(
    'name'=> 'CreatorType',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezpackagecreationhandler.php',
    'class' => 'eZPackageCreationHandler',
    'function' => 'creatorLimitationList',
    'parameter' => array(  false )
    );

$RoleID = array(
    'name'=> 'Role',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezpackage.php',
    'class' => 'eZPackage',
    'function' => 'maintainerRoleListForRoles',
    'parameter' => array(  false )
    );


$FunctionList = array();
$FunctionList['read'] = array( 'Type' => $TypeID );
$FunctionList['list'] = array( 'Type' => $TypeID );
$FunctionList['create'] = array( 'Type' => $TypeID,
                                 'CreatorType' => $CreatorTypeID,
                                 'Role' => $RoleID );
$FunctionList['edit'] = array( 'Type' => $TypeID );
$FunctionList['remove'] = array( 'Type' => $TypeID );
$FunctionList['install'] = array( 'Type' => $TypeID );
$FunctionList['import'] = array( 'Type' => $TypeID );
$FunctionList['export'] = array( 'Type' => $TypeID );

?>
