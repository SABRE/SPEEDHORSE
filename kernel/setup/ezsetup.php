<?php
//
// eZSetup
//
// Created on: <08-Nov-2002 11:00:54 kd>
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

$GLOBALS['eZSiteBasics']['no-cache-adviced'] = false;

// Avoid compiling templates just for 1 view to improve performance
$GLOBALS['eZTemplateCompilerSettings']['compile'] = false;

// Include common functions
include_once( "kernel/setup/ezsetupcommon.php" );
include_once( "kernel/setup/ezsetuptests.php" );
include_once( 'kernel/setup/ezsetup_summary.php' );

// Initialize template
$tpl = eZTemplate::instance();
//$tpl->registerFunction( "section", new eZTemplateSectionFunction( "section" ) );
//$tpl->registerFunction( "include", new eZTemplateIncludeFunction() );

$ini = eZINI::instance();
if ( $ini->variable( 'TemplateSettings', 'Debug' ) == 'enabled' )
    eZTemplate::setIsDebugEnabled( true );
//eZDebug::setLogOnly( true );

//$ini->setVariable( 'RegionalSettings', 'TextTranslation', 'disabled' );


$Module = $Params['Module'];

$tpl->setAutoloadPathList( $ini->variable( 'TemplateSettings', 'AutoloadPathList' ) );
$tpl->autoload();

$tpl->registerResource( eZTemplateDesignResource::instance() );

// Initialize HTTP variables
$http = eZHTTPTool::instance();

$baseDir = 'kernel/setup/';

// Load step list data. See this file for install step references.
$stepDataFile = $baseDir . "steps/ezstep_data.php";
$stepData = null;
if ( file_exists( $stepDataFile ) )
{
    include_once( $stepDataFile );
    $stepData = new eZStepData();
}
if ( $stepData == null )
{
    print "<h1>Setup step data file not found. Setup is exiting...</h1>"; //TODO : i18n translate
    eZDisplayResult( $templateResult, eZDisplayDebug() );
    eZExecution::cleanExit();
}

$persistenceList = eZSetupFetchPersistenceList();
$result = null;

// process previous step
$previousStepClass = null;
$step = null;
$currentStep = null;

if ( $http->hasPostVariable( 'eZSetup_back_button' ) ) // previous step selected
{
    $previousStep = $http->postVariable( 'eZSetup_current_step' );
    $step = $stepData->previousStep( $previousStep );
    $goBack = true;
    while ( $goBack )
    {
        $includeFile = $baseDir .'steps/ezstep_'.$step['file'].'.php';

        if ( file_exists( $includeFile ) )
        {
            include_once( $includeFile );
            $className = 'eZStep'.$step['class'];
            $stepObject = new $className( $tpl, $http, $ini, $persistenceList );

            if ( $stepObject->init() === true )
            {
                $step = $stepData->previousStep( $step );
                continue;
            }
        }

        $goBack = false;
    }

}
else if ( $http->hasPostVariable( 'eZSetup_refresh_button' ) ) // refresh selected step
{
    $step = $stepData->step( $http->postVariable( 'eZSetup_current_step' ) );
}
else if ( $http->hasPostVariable( 'eZSetup_next_button' ) || $http->hasPostVariable( 'eZSetup_current_step' ) ) // next step selected,
{
    // first, input from step must be processed/checked (processPostData())
    $currentStep = $stepData->step( $http->postVariable( 'eZSetup_current_step' ) );

    $includeFile = $baseDir .'steps/ezstep_'.$currentStep['file'].'.php';
    $result = array();

    if ( file_exists( $includeFile ) )
    {
        include_once( $includeFile );
        $className = 'eZStep'.$currentStep['class'];
        $previousStepClass = new $className( $tpl, $http, $ini, $persistenceList );

        $processPostDataResult = $previousStepClass->processPostData();
        $persistenceList = $previousStepClass->PersistenceList;

        if ( $processPostDataResult === false ) // processing previous input failed, step must be redone
        {
            $step = $currentStep;
        }
        else if ( $processPostDataResult !== true ) // step to redo specified
        {
            $step = $stepData->step( $processPostDataResult );
        }
        else
        {
            $step = $stepData->nextStep( $currentStep );
        }
    }

}
else //First step, no params set.
{
    $step = $stepData->step(0); //step contains file and class
}

$done = false;
$result = null;

while( !$done && $step != null )
{
// Some common variables for all steps
    $tpl->setVariable( "script", eZSys::serverVariable( 'PHP_SELF' ) );

    $siteBasics = $GLOBALS['eZSiteBasics'];
    $useIndex = $siteBasics['validity-check-required'];

    if ( $useIndex )
        $script = eZSys::wwwDir() . eZSys::indexFileName();
    else
        $script = eZSys::indexFile() . "/setup/$partName";
    $tpl->setVariable( 'script', $script );

    $tpl->setVariable( "version", array( "text" => eZPublishSDK::version(),
                                         "major" => eZPublishSDK::majorVersion(),
                                         "minor" => eZPublishSDK::minorVersion(),
                                         "release" => eZPublishSDK::release(),
                                         "alias" => eZPublishSDK::alias() ) );

    if ( $persistenceList === null )
        $persistenceList = eZSetupFetchPersistenceList();
    $tpl->setVariable( 'persistence_list', $persistenceList );

    // Try to include the relevant file
    $includeFile = $baseDir . 'steps/ezstep_'.$step['file'].'.php';
    $stepClass = false;
    if ( file_exists( $includeFile ) )
    {
        include_once( $includeFile );
        $className = 'eZStep'.$step['class'];

        if ( $step == $currentStep ) // if processing post data of current step failed, use same class object.
        {
            $stepInstaller = $previousStepClass;
        }
        else
        {
            $stepInstaller = new $className( $tpl, $http, $ini, $persistenceList );
        }

        $result = $stepInstaller->init();

        if( $result === true )
        {
            $step = $stepData->nextStep( $step );
        }
        else if( is_int( $result ) || is_string( $result ) )
        {
            $step = $stepData->step( $result );
        }
        else
        {
            $tpl->setVariable( 'setup_current_step', $step['class'] ); // set current step
            $result = $stepInstaller->display();
            $result['help'] = $tpl->fetch( 'design:setup/init/'.$step['file'].'_help.tpl' );
            $done = true;
        }
    }
    else
    {
        print( '<h1>Step '.$step['class'].' is not valid, no such file '.$includeFile.'. I\'m exiting...</h1>' ); //TODO : i18n
        eZDisplayResult( $templateResult, eZDisplayDebug() );
        eZExecution::cleanExit();
    }
}

// generate summary
$summary = new eZSetupSummary( $tpl, $persistenceList );
$result['summary'] = $summary->summary();

// Compute install progress
$result['progress'] = $stepData->progress( $step );

// Print debug information and exit.
eZDebug::addTimingPoint( "End" );

return $result;

//eZDisplayResult( $templateResult, eZDisplayDebug() );

//eZExecution::cleanExit();
?>
