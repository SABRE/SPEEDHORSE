<?php
//
// Created on: <21-Nov-2003 11:37:53 amos>
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

$http = eZHTTPTool::instance();

$creator = false;
$initializeStep = false;
if ( $module->isCurrentAction( 'CreatePackage' ) )
{
    $creatorID = $module->actionParameter( 'CreatorItemID' );
    if ( $creatorID )
    {
        $creator = eZPackageCreationHandler::instance( $creatorID );
        $persistentData = array();
        $http->setSessionVariable( 'eZPackageCreatorData' . $creatorID, $persistentData );
        $initializeStep = true;
        $package = false;
        if ( isset( $persistentData['package_name'] ) )
            $package = eZPackage::fetch( $persistentData['package_name'] );
        $creator->generateStepMap( $package, $persistentData );
    }
}
else if ( $module->isCurrentAction( 'PackageStep' ) )
{
    if ( $module->hasActionParameter( 'CreatorItemID' ) )
    {
        $creatorID = $module->actionParameter( 'CreatorItemID' );
        $creator = eZPackageCreationHandler::instance( $creatorID );
        if ( $http->hasSessionVariable( 'eZPackageCreatorData' . $creatorID ) )
            $persistentData = $http->sessionVariable( 'eZPackageCreatorData' . $creatorID );
        else
            $persistentData = array();
        $package = false;
        if ( isset( $persistentData['package_name'] ) )
            $package = eZPackage::fetch( $persistentData['package_name'] );
        $creator->generateStepMap( $package, $persistentData );
    }
}

$tpl = eZTemplate::factory();

$templateName = 'design:package/create.tpl';
if ( $creator )
{
    $currentStepID = false;
    if ( $module->hasActionParameter( 'CreatorStepID' ) )
        $currentStepID = $module->actionParameter( 'CreatorStepID' );
    $steps =& $creator->stepMap();
    if ( !isset( $steps['map'][$currentStepID] ) )
        $currentStepID = $steps['first']['id'];
    $errorList = array();
    $hasAdvanced = false;

    $lastStepID = $currentStepID;
    if ( $module->hasActionParameter( 'NextStep' ) )
    {
        $hasAdvanced = true;
        $currentStepID = $creator->validateStep( $package, $http, $currentStepID, $steps, $persistentData, $errorList );
        if ( $currentStepID != $lastStepID )
        {
            $lastStep =& $steps['map'][$lastStepID];
            $creator->commitStep( $package, $http, $lastStep, $persistentData, $tpl );
            $initializeStep = true;
        }
    }

    if ( $currentStepID )
    {
        $currentStep =& $steps['map'][$currentStepID];

        $stepTemplate = $creator->stepTemplate( $currentStep );
        $stepTemplateName = $stepTemplate['name'];
        $stepTemplateDir = $stepTemplate['dir'];

        if ( $initializeStep )
            $creator->initializeStep( $package, $http, $currentStep, $persistentData, $tpl );

        $creator->loadStep( $package, $http, $currentStepID, $persistentData, $tpl, $module );
        if ( $package )
            $persistentData['package_name'] = $package->attribute( 'name' );

        $http->setSessionVariable( 'eZPackageCreatorData' . $creatorID, $persistentData );

        $tpl->setVariable( 'creator', $creator );
        $tpl->setVariable( 'current_step', $currentStep );
        $tpl->setVariable( 'persistent_data', $persistentData );
        $tpl->setVariable( 'error_list', $errorList );
        $tpl->setVariable( 'package', $package );

        $templateName = "design:package/$stepTemplateDir/$stepTemplateName";
    }
    else
    {
        $creator->finalize( $package, $http, $persistentData );
        $package->setAttribute( 'is_active', true );
        $http->removeSessionVariable( 'eZPackageCreatorData' . $creatorID );
        if ( $package )
            return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );
        else
            return $module->redirectToView( 'list' );
    }
}
else
{
    $creators =& eZPackageCreationHandler::creatorList( true );

    $tpl->setVariable( 'creator_list', $creators );
}

$Result = array();
$Result['content'] = $tpl->fetch( $templateName );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/package', 'Create package' ) ) );
?>
