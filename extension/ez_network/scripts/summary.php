<?php
//
// Created on: <28-Mar-2007 16:11:00 vp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Network
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
//     Att: eZ Systems AS Licensing Dept., Klostergata 30, N-3732 Skien, Norway
//
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file summary.php
*/

/*!
  \class eZPNetSummary summary.php
  \brief The class eZPNetSummary does

*/

require 'autoload.php';
require 'extension/ez_network/classes/include_all.php';

define( 'EZ_NETWORK_EXTENSION_TYPE_SIMPLE', 1 );
define( 'EZ_NETWORK_EXTENSION_TYPE_DESIGN', 2 );
define( 'EZ_NETWORK_EXTENSION_TYPE_MODULE', 3 );

@ini_set( 'memory_limit', '500M' );

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'debug-message' => '',
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions();

$endl = $cli->endlineString();
$webOutput = $cli->isWebOutput();

function help()
{
    $argv = $_SERVER['argv'];
    $cli = eZCLI::instance();
    $cli->output( "Usage: " . $argv[0] . " [OPTION]... [PART]\n" .
                  "Shows eZ Publish extensions list with types.\n" .
                  "\n" .
                  "General options:\n" .
                  "  -h,--help          display this help and exit \n" .
                  "  -d,--debug         display debug output at end of execution\n" .
                  "  -c,--colors        display output using ANSI colors\n" .
//                  "  --sql              display sql queries\n" .
                  "  --no-colors        do not use ANSI coloring (default)\n" );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for initialize script" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

$siteaccess = false;
$debugOutput = false;
$allowedDebugLevels = false;
$useDebugAccumulators = false;
$useDebugTimingpoints = false;
$useIncludeFiles = false;
$useColors = false;
$isQuiet = false;
$useLogFiles = false;
$showSQL = false;

$optionsWithData = array( 's' );
$longOptionsWithData = array( 'siteaccess' );

$readOptions = true;

for ( $i = 1; $i < count( $argv ); ++$i )
{
    $arg = $argv[$i];
    if ( $readOptions and
         strlen( $arg ) > 0 and
         $arg[0] == '-' )
    {
        if ( strlen( $arg ) > 1 and
             $arg[1] == '-' )
        {
            $flag = substr( $arg, 2 );
            if ( in_array( $flag, $longOptionsWithData ) )
            {
                $optionData = $argv[$i+1];
                ++$i;
            }
            if ( $flag == 'help' )
            {
                help();
                exit();
            }
            else if ( $flag == 'debug' )
            {
                $debugOutput = true;
            }
            else if ( $flag == 'colors' )
            {
                $useColors = true;
            }
            else if ( $flag == 'no-colors' )
            {
                $useColors = false;
            }
            else if ( $flag == 'no-logfiles' )
            {
                $useLogFiles = false;
            }
            else if ( $flag == 'logfiles' )
            {
                $useLogFiles = true;
            }
            else if ( $flag == 'sql' )
            {
                $showSQL = true;
            }
        }
        else
        {
            $flag = substr( $arg, 1, 1 );
            $optionData = false;
            if ( in_array( $flag, $optionsWithData ) )
            {
                if ( strlen( $arg ) > 2 )
                {
                    $optionData = substr( $arg, 2 );
                }
                else
                {
                    $optionData = $argv[$i+1];
                    ++$i;
                }
            }
            if ( $flag == 'h' )
            {
                help();
                exit();
            }
            else if ( $flag == 'c' )
            {
                $useColors = true;
            }
            else if ( $flag == 'd' )
            {
                $debugOutput = true;
                if ( strlen( $arg ) > 2 )
                {
                    $levels = explode( ',', substr( $arg, 2 ) );
                    $allowedDebugLevels = array();
                    foreach ( $levels as $level )
                    {
                        if ( $level == 'all' )
                        {
                            $useDebugAccumulators = true;
                            $allowedDebugLevels = false;
                            $useDebugTimingpoints = true;
                            break;
                        }
                        if ( $level == 'accumulator' )
                        {
                            $useDebugAccumulators = true;
                            continue;
                        }
                        if ( $level == 'timing' )
                        {
                            $useDebugTimingpoints = true;
                            continue;
                        }
                        if ( $level == 'include' )
                        {
                            $useIncludeFiles = true;
                        }
                        if ( $level == 'error' )
                            $level = EZ_LEVEL_ERROR;
                        else if ( $level == 'warning' )
                            $level = EZ_LEVEL_WARNING;
                        else if ( $level == 'debug' )
                            $level = EZ_LEVEL_DEBUG;
                        else if ( $level == 'notice' )
                            $level = EZ_LEVEL_NOTICE;
                        else if ( $level == 'timing' )
                            $level = EZ_LEVEL_TIMING;
                        $allowedDebugLevels[] = $level;
                    }
                }
            }
        }
    }
}
$script->setUseDebugOutput( $debugOutput );
$script->setAllowedDebugLevels( $allowedDebugLevels );
$script->setUseDebugAccumulators( $useDebugAccumulators );
$script->setUseDebugTimingPoints( $useDebugTimingpoints );
$script->setUseIncludeFiles( $useIncludeFiles );

if ( $webOutput )
    $useColors = true;

$cli->setUseStyles( $useColors );
$script->setDebugMessage( "\n\n" . str_repeat( '#', 36 ) . $cli->style( 'emphasize' ) . " DEBUG " . $cli->style( 'emphasize-end' )  . str_repeat( '#', 36 ) . "\n" );

$script->initialize();
if ( !$script->isInitialized() )
{
    $cli->error( 'Error initializing script: ' . $script->initializationError() . '.' );
    $script->shutdown();
    exit();
}

if ( !function_exists( 'readline' ) )
{
    function readline( $prompt = '' )
        {
            echo $prompt . ' ';
            return trim( fgets( STDIN ) );
        }
}

if ( !function_exists( 'getUserInput' ) )
{
    function getUserInput( $query, $acceptValues )
    {
        $validInput = false;
        while( !$validInput )
        {
            $input = readline( $query );
            if ( $acceptValues === false ||
                 in_array( $input, $acceptValues ) )
            {
                $validInput = true;
            }
        }
        return $input;
    }
}

$cli->setUseStyles( true );

$totalCounter = 0;
$typedCounter = array( EZ_NETWORK_EXTENSION_TYPE_SIMPLE => 0,
                      EZ_NETWORK_EXTENSION_TYPE_DESIGN => 0,
                      EZ_NETWORK_EXTENSION_TYPE_MODULE => 0
                      );
$typeNames = array( EZ_NETWORK_EXTENSION_TYPE_SIMPLE => 'simple extension',
                    EZ_NETWORK_EXTENSION_TYPE_DESIGN => 'design extension',
                    EZ_NETWORK_EXTENSION_TYPE_MODULE => 'module'
                    );

$extensionNameList = eZDir::findSubItems( eZExtension::baseDirectory(), 'dl' );

foreach ( $extensionNameList as $extensionName )
{
    $extensionType = eZPNetSummary::getExtensionType( $extensionName );
    $cli->output( "$extensionName ({$typeNames[$extensionType]})" );

    $typedCounter[$extensionType]++;
    $totalCounter++;
}

$cli->output( "" );
foreach ( $typedCounter as $typeID => $typeCount )
{
    $cli->output( $typeNames[$typeID] . 's: ' . $typedCounter[$typeID] );
}
$cli->output( "Total extensions: $totalCounter\n" );

$script->shutdown();



class eZPNetSummary
{

    /*!
     \static
     Get extension type

     \param extensionName

     \return Extension type ID
    */
    function getExtensionType( $extensionName )
    {
        $extensionType = EZ_NETWORK_EXTENSION_TYPE_SIMPLE;

        $currentExtensionPath = eZDir::path( array( eZExtension::baseDirectory(), $extensionName ) );

        // check files in the extension root directory
        $rootFileList = eZDir::findSubitems( $currentExtensionPath, 'f', false, false, '/^ezinfo.php$/' );
        foreach ( $rootFileList as $rootFile )
        {
            if ( preg_match( '/\.php$/', $rootFile ) )
            {
                return EZ_NETWORK_EXTENSION_TYPE_MODULE;
            }
            elseif ( preg_match( '/\.tpl$/', $rootFile ) )
            {
                $extensionType = EZ_NETWORK_EXTENSION_TYPE_DESIGN;
            }
        }

        // search files in subdirectories
        $dirList = eZDir::findSubdirs( $currentExtensionPath, false, '/^settings$/' );
        foreach ( $dirList as $dir )
        {
            $phpFileList = eZDir::recursiveFindRelative( $currentExtensionPath, $dir, "\.php" );
            if ( $phpFileList )
            {
                return EZ_NETWORK_EXTENSION_TYPE_MODULE;
            }

            if ( $extensionType == EZ_NETWORK_EXTENSION_TYPE_DESIGN )
            {
                continue;
            }

            $tplFileList = eZDir::recursiveFindRelative( $currentExtensionPath, $dir, "\.tpl" );
            if ( $tplFileList )
            {
                $extensionType = EZ_NETWORK_EXTENSION_TYPE_DESIGN;
            }
        }

        return $extensionType;
    }

}

?>
