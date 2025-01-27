#!/usr/bin/env php
<?php
//
// Definition of Initialize class
//
// Created on: <18-Oct-2005 09:33:51 hovik>
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

/*! \file initialize.php
*/

/*!
  \class Initialize initialize.php
  \brief The class Initialize does

*/
require 'autoload.php';
require 'extension/ez_network/classes/include_all.php';

@ini_set( 'memory_limit', '500M' );

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli = eZCLI::instance();
    if ( file_exists( eZSiteAccess::findPathToSiteAccess( $optionData ) ) )
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

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => "Executes eZ Publish Network initialation script.",
                                      'debug-message' => '',
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );
$script->startup();

$options = $script->getOptions( '[version][installationkey:][replacekey]',
                                '',
                                array( 'version' => 'Show eZ Network version.',
                                       'replacekey' => 'Replace installation key',
                                       'installationkey' => 'Installation key',
                                       ) );
$script->initialize();

$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;

if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
}


if ( !$script->isInitialized() )
{
    $cli->error( 'Error initializing script: ' . $script->initializationError() . '.' );
    $script->shutdown();
    exit();
}

// Output current eZ Network version
if ( $options['version'] )
{
    $networkInfo = eZNetUtils::extensionInfo( eZINI::instance( 'network.ini' )->variable( 'NetworkSettings', 'ExtensionPath' ) );
    if ( $networkInfo['version'] )
    {
        $cli->output( $networkInfo['name'] . ' client ' . $networkInfo['version'] );
    }
    else
    {
        $cli->output( 'Version not set. eZ Network may not be installed yet.' );
    }
    $script->shutdown();
    exit();
}


// Script start
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

// Begin transation
$db = eZDB::instance();
$db->begin();

$schemaFilePath = eZExtension::baseDirectory() . '/'
                . eZINI::instance( 'network.ini' )->variable( 'NetworkSettings', 'ExtensionPath' )
                . '/share/db_schema.dba';

$dbContents = eZDbSchema::read( $schemaFilePath, true );
if ( $dbContents === false )
{
    $db->rollback();
    $cli->output( 'Failed to load DB definition file : ' . $schemaFilePath );
    $script->shutdown();
    exit();
}

$replaceKey = ( $options[ 'replacekey' ] == null ) ? false : true;

// Fetch current installation key
$installationKey = false;
$newInstallationKey = false;

$resultSet = $db->arrayQuery( "SELECT value FROM ezsite_data WHERE name='ezpublish_site_id'" );
if ( count( $resultSet ) == 1 )
{
    $installationKey = $resultSet[0]['value'];
    $cli->output( 'Found existing installation key: ' . $installationKey );
}

// Reset existing key if replacing
if ( $replaceKey )
{
    $installationKey = '';
}


// Use installation key parameter ?
if ( $options[ 'installationkey' ] != null )
{
    $newInstallationKey = $options[ 'installationkey' ];
}

// Ask user for key?
if ( $installationKey == false && $newInstallationKey == false )
{
    $cli->output( "\nA valid installation key is needed for authentication against eZ Systems servers.
You will find your installation key by accessing your Service portal at http://support.ez.no/\n
If a valid installation key is not provided it will be generated later,
but you are then required to contact support to register it.\n" );
    $newInstallationKey = getUserInput( 'Installation key provided by eZ Systems(leave empty to generate a new):', false );

    // Validating key
    if ( strlen( $newInstallationKey ) != 32 )
    {
        $newInstallationKey = md5( mt_rand() . '.' . time() . '.' . mt_rand() );
        $cli->output( "Generated $newInstallationKey\n" );
    }

}

// Insert new installation key
if ( $installationKey == false )
{
    $installationKey = $newInstallationKey;
    $db->query( "DELETE FROM ezsite_data WHERE name='ezpublish_site_id'" );
    $db->query( "INSERT INTO ezsite_data ( name, value ) values ( 'ezpublish_site_id', '" . $installationKey . "' )" );
}

// Get DB schema.
$schema = isset( $dbContents['schema'] ) ? $dbContents['schema'] : false;
if ( $schema === false )
{
    $db->rollback();
    $cli->output( 'Failed to retrieve schema from DB definition file: ' . $schemaFilePath );
    $script->shutdown();
    exit();
}

// Create or update DB schema.
$cli->output( 'Creating DB schema.' );
eZNetUtils::createTable( $schema );
$cli->output( 'Database initialized.' );


// Store all ini files to DB, and create timestamp log.
$cli->output( 'Creating INI settings snapshot.' );
$ini = eZINI::instance();

$currentAccess = $GLOBALS['eZCurrentAccess']['name'];
$availableSiteAccesses = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

include_once( 'access.php' );

$timestampArray = array();
$iniFileNameList = eZNetUtils::iniFileNameList();

foreach( $availableSiteAccesses as $siteAccess )
{
    $GLOBALS['eZCurrentAccess']['name'] = $siteAccess;
    changeAccess( array( 'name' => $siteAccess ) );

    $timestampArray[$siteAccess] = array();

    foreach( $iniFileNameList as $iniFile )
    {
        $timestampArray[$siteAccess][$iniFile] = array();
        $file = '';
        $ini = eZINI::instance( $iniFile );
        $ini->findInputFiles( $inputFiles, $file );

        foreach( $inputFiles as $inputFile )
        {
            eZNetUtils::addSettingsFile( $inputFile, $siteAccess );
            $timestampArray[$siteAccess][$iniFile][$inputFile] = filemtime( $inputFile );
            $script->iterate( $cli, true );
        }
    }
}
eZNetStorage::set( eZNetUtils::SETTINGS_KEY, $timestampArray, eZNetUtils::nodeID() );
$cli->output( '' );
$cli->output( 'Done creating INI settings snapshot.' );

// Store role setup
$roleArray = eZNetUtils::getRoleIDList();
eZNetStorage::set( eZNetUtils::ROLE_KEY, $roleArray );
$cli->output( 'Stored Roles specification.' );

// Store md5 Sums using the net utils caclulation method.
$cli->output( 'Storing eZ Publish MD5 sums.' );
$fileListName = 'share/filelist.md5';
if ( !file_exists( $fileListName ) )
{
    $db->rollback();
    $cli->output( 'Failed to load MD5 sums file : ' . $fileListName );
    $script->shutdown();
    exit();
}
$fileList = file_get_contents( $fileListName );
$fileList = explode( "\n", $fileList );
$totalCount = count( $fileList );
foreach( $fileList as $count => $line )
{
    if ( $count % 20 == 0 )
    {
        echo "\r   " . (int)( $count / $totalCount * 100 + 1 ) . " %";
    }
    if ( strlen( $line ) )
    {
        list( $oldMD5, $tmp, $filename ) = explode( " ", $line );
        eZNetUtils::setFileMD5( $filename );
    }
}

// Set client version
$cli->output( 'Settings network client version.' );
$clientInfo = eZNetClientInfo::instance();
$clientInfo->updateVersion();

// Create crontab suggestion
$phpExec = eZInitializeTools::getPHPFullPath();
$ini = eZINI::instance();

$rndMinute  = mt_rand(3, 40);
$rndHour    = mt_rand(2, 6);
$rndMinute2 = $rndMinute + 11;

$crontabTemplate = 'PHPEXEC_WARNING
# -- ' . $ini->variable( 'SiteSettings', 'SiteName' ) . " -- eZ Network crontab setup --
COMMENTEDezpath=INSTALLATION_PATH
COMMENTEDphpbin=\"PHP_EXEC -d memory_limit=512M -d safe_mode=0 -d disable_functions=0\"

COMMENTED$rndMinute */8 * * * cd \$ezpath && \$phpbin runcronjobs.php -q sync_network >/dev/null 2>&1
COMMENTED$rndMinute2 $rndHour * * *   cd \$ezpath && \$phpbin runcronjobs.php -q patch >/dev/null 2>&1
COMMENTED1 * * * *    cd \$ezpath && \$phpbin runcronjobs.php -q monitor >/dev/null 2>&1
";
$crontabTemplate = str_replace( 'INSTALLATION_PATH', escapeshellarg( getcwd() ), $crontabTemplate );
if ( $phpExec !== false )
{
    $crontabTemplate = str_replace( 'PHP_EXEC', $phpExec, $crontabTemplate );
    $crontabTemplate = str_replace( 'COMMENTED', '', $crontabTemplate );
    $crontabTemplate = str_replace( 'PHPEXEC_WARNING', '', $crontabTemplate );
}
else
{
    // If php cli has not been found
    // we should comment out each of command line
    // and show warning message about correct path to php executable should be added before the commands can be executed
    $crontabTemplate = str_replace( 'COMMENTED', '#', $crontabTemplate );
    $phpExecWarning = 'The path to the php CLI in the cron commands has to be mended before they can be executed!';
    $cli->output( $phpExecWarning );
    $crontabTemplate = str_replace( 'PHPEXEC_WARNING', '#' . $phpExecWarning, $crontabTemplate );
}

eZFile::create( 'nw_crontab.txt', false, $crontabTemplate );
$cli->output( 'Installation key: ' . $installationKey );

// Commit DB session.
$db->commit();

$cli->output( 'Done initializing!' );
$cli->output( '' );
$cli->output( 'Created crontab template file "nw_crontab.txt" in eZ Publish root folder:' );
$cli->output( $crontabTemplate );
$script->shutdown();
exit();


class eZInitializeTools
{
    /*!
     \static

     Get full PHP-CLI path
     If it cannot be fetched from $_SERVER it tries to fetch just php executable from ini
     */
    function getPHPFullPath()
    {
        // Trying to fetch phpcli from command line
        $phpcli = isset( $_SERVER['_'] ) ? $_SERVER['_'] : false;
        if ( $phpcli and !empty( $phpcli ) )
        {
            return $phpcli;
        }

        // If php cli from SERVER has not been found try to fetch from network.ini
        $ini = eZINI::instance( 'network.ini' );
        $phpcli = $ini->hasVariable( 'NetworkSettings', 'PHPCLI' ) ? $ini->variable( 'NetworkSettings', 'PHPCLI' ) : false;

        return $phpcli;
    }

    /*!
     \static

     Reads and returns user input.
    */
    function readline( $prompt = '' )
    {
        echo $prompt . ' ';
        return trim( fgets( STDIN ) );
    }

    /*!
     \static

     Prints \a $query,
     Returns and validates user input \a $acceptValues
    */
    function getUserInput( $query, $acceptValues = false )
    {
        $validInput = false;
        while( !$validInput )
        {
            $input = eZInitializeTools::readline( $query );
            if ( $acceptValues === false || in_array( $input, $acceptValues ) )
            {
                $validInput = true;
            }
        }

        return $input;
    }

    /*!
     \static
     Gets user input and returns entered symbol
    */
    function confirmLocation( $location = false )
    {
        if ( !$location )
            return false;

        $input = eZInitializeTools::getUserInput( 'Confirm the path \'' . $location . '\' [y/n] or \'s\' to skip:', array( 'y', 'n', 's' ) );
        return $input;
    }
}

?>
