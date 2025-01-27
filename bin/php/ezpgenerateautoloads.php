#!/usr/bin/env php
<?php
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

if ( file_exists( "config.php" ) )
{
    require "config.php";
}

// Setup, includes
//{
$useBundledComponents = defined( 'EZP_USE_BUNDLED_COMPONENTS' ) ? EZP_USE_BUNDLED_COMPONENTS === true : file_exists( 'lib/ezc' );
if ( $useBundledComponents )
{
    set_include_path( './lib/ezc' . PATH_SEPARATOR . get_include_path() );
    require 'Base/src/base.php';
}
else if ( defined( 'EZC_BASE_PATH' ) )
{
    require EZC_BASE_PATH;
}
else
{
    if ( !@include 'ezc/Base/base.php' )
    {
        require 'Base/src/base.php';
    }
}

spl_autoload_register( array( 'ezcBase', 'autoload' ) );

require 'kernel/private/classes/ezautoloadgenerator.php';
require 'kernel/private/interfaces/ezpautoloadoutput.php';
require 'kernel/private/classes/ezpautoloadclioutput.php';
require 'kernel/private/options/ezpautoloadgeneratoroptions.php';
require 'kernel/private/structs/ezpautoloadfilefindcontext.php';

//}

// Setup console parameters
//{
$params = new ezcConsoleInput();

$helpOption = new ezcConsoleOption( 'h', 'help' );
$helpOption->mandatory = false;
$helpOption->shorthelp = "Show help information";
$params->registerOption( $helpOption );

$targetOption = new ezcConsoleOption( 't', 'target', ezcConsoleInput::TYPE_STRING );
$targetOption->mandatory = false;
$targetOption->shorthelp = "The directory to where the generated autoload file should be written.";
$params->registerOption( $targetOption );

$verboseOption = new ezcConsoleOption( 'v', 'verbose', ezcConsoleInput::TYPE_NONE );
$verboseOption->mandatory = false;
$verboseOption->shorthelp = "Whether or not to display more information.";
$params->registerOption( $verboseOption );

$dryrunOption = new ezcConsoleOption( 'n', 'dry-run', ezcConsoleInput::TYPE_NONE );
$dryrunOption->mandatory = false;
$dryrunOption->shorthelp = "Whether a new file autoload file should be written.";
$params->registerOption( $dryrunOption );

$kernelFilesOption = new ezcConsoleOption( 'k', 'kernel', ezcConsoleInput::TYPE_NONE );
$kernelFilesOption->mandatory = false;
$kernelFilesOption->shorthelp = "If an autoload array for the kernel files should be generated.";
$params->registerOption( $kernelFilesOption );

$kernelOverrideOption = new ezcConsoleOption( 'o', 'kernel-override', ezcConsoleInput::TYPE_NONE );
$kernelOverrideOption->mandatory = false;
$kernelOverrideOption->shorthelp = "If an autoload array for the kernel override files should be generated.";
$params->registerOption( $kernelOverrideOption );

$extensionFilesOption = new ezcConsoleOption( 'e', 'extension', ezcConsoleInput::TYPE_NONE );
$extensionFilesOption->mandatory = false;
$extensionFilesOption->shorthelp = "If an autoload array for the extensions should be generated.";
$params->registerOption( $extensionFilesOption );

$testFilesOption = new ezcConsoleOption( 's', 'tests', ezcConsoleInput::TYPE_NONE );
$testFilesOption->mandatory = false;
$testFilesOption->shorthelp = "If an autoload array for the tests should be generated.";
$params->registerOption( $testFilesOption );

$excludeDirsOption = new ezcConsoleOption( '', 'exclude', ezcConsoleInput::TYPE_STRING );
$excludeDirsOption->mandatory = false;
$excludeDirsOption->shorthelp = "Folders to exclude from the class search.";
$params->registerOption( $excludeDirsOption );

$displayProgressOption = new ezcConsoleOption( 'p', 'progress', ezcConsoleInput::TYPE_NONE );
$displayProgressOption->mandatory = false;
$displayProgressOption->shorthelp = "If progress output should be shown on the command-line.";
$params->registerOption( $displayProgressOption );

// Add an argument for which extension to search
$params->argumentDefinition = new ezcConsoleArguments();

$params->argumentDefinition[0] = new ezcConsoleArgument( 'extension' );
$params->argumentDefinition[0]->mandatory = false;
$params->argumentDefinition[0]->shorthelp = "Extension to generate autoload files for.";
$params->argumentDefinition[0]->default = getcwd();

// Process console parameters
try
{
    $params->process();
}
catch ( ezcConsoleOptionException $e )
{
    print( $e->getMessage(). "\n" );
    print( "\n" );

    echo $params->getHelpText( 'Autoload file generator.' ) . "\n";

    echo "\n";
    exit();
}

if ( $helpOption->value === true )
{
    echo $params->getHelpText( 'Autoload file generator.' ) . "\n";
    exit();
}
//}

if ( $excludeDirsOption->value !== false )
{
    $excludeDirs = explode( ',', $excludeDirsOption->value );
}
else
{
    $excludeDirs = array();
}

$autoloadOptions = new ezpAutoloadGeneratorOptions();

$autoloadOptions->basePath = $params->argumentDefinition['extension']->value;
$autoloadOptions->searchKernelFiles = $kernelFilesOption->value;
$autoloadOptions->searchKernelOverride = $kernelOverrideOption->value;
$autoloadOptions->searchExtensionFiles = $extensionFilesOption->value;
$autoloadOptions->searchTestFiles = $testFilesOption->value;
$autoloadOptions->writeFiles = !$dryrunOption->value;
$autoloadOptions->displayProgress = $displayProgressOption->value;

if ( !empty( $targetOption->value ) )
{
    $autoloadOptions->outputDir = $targetOption->value;
}
$autoloadOptions->excludeDirs = $excludeDirs;

$autoloadGenerator = new eZAutoloadGenerator( $autoloadOptions );

if ( defined( 'EZP_AUTOLOAD_OUTPUT' ) )
{
    $outputClass = EZP_AUTOLOAD_OUTPUT;
    $autoloadCliOutput = new $outputClass();
}
else
{
    $autoloadCliOutput = new ezpAutoloadCliOutput();
}

$autoloadGenerator->setOutputObject( $autoloadCliOutput );
$autoloadGenerator->setOutputCallback( array( $autoloadCliOutput, 'outputCli') );

try
{
    $autoloadGenerator->buildAutoloadArrays();

    // If we are showing progress output, let's print the list of warnings at
    // the end.
    if ( $displayProgressOption->value )
    {
        $warningMessages = $autoloadGenerator->getWarnings();
        foreach ( $warningMessages as $msg )
        {
            $autoloadCliOutput->outputCli( $msg, "warning" );
        }
    }

    if ( $verboseOption->value )
    {
        $autoloadGenerator->printAutoloadArray();
    }
}
catch (Exception $e)
{
    echo $e->getMessage() . "\n";
}

?>
