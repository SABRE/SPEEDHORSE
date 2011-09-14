<?php
//
// Created on: <20-Jul-2007 10:30:20 hovik>
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

/*! \file downloadclient.php
*/

require 'autoload.php';
require 'extension/ez_network/classes/include_all.php';

@ini_set( 'memory_limit', '500M' );

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

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => 'Downloads a new version of the eZ Network extension.' . "\n" .
                                                      'The extension will be placed in the root directory of your eZ Publish installation.'  . "\n" .
                                                      'If the --install option is used the new version will be installed automatically.',
                                     'debug-message' => '',
                                     'use-session' => true,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );
$script->startup();

$options = $script->getOptions( "[install]",
                                '',
                                array( 'install' => 'Automatically install/unpack the extension' ) );
$script->initialize();

// We should create instance of eZNetClientInfo after script initializing
// because default siteaccess should be fetched and inited before instancing of DB.
// eZScript::initialize() initializes default access, so we can instance DB after that.
// (DB tries to connect to DB server using login data from ini, if we don't init default siteaccess wrong login data will be used)
$eznetClientInfo = eZNetClientInfo::instance();

$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;
$installExtension = $options['install'] ? true : false;

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

// Start script
if ( !eZNetUtils::canCreateLog() )
{
    $cli->output( 'Could not write to log file: var/log/network/network.log' . "\n" .
                  'Exiting ...' );
    $script->shutdown();
    exit();
}

eZNetUtils::log( 'Starting extension download' );

$installation = eZNetInstallation::fetchCurrent();
if ( !$installation )
{
    $message = 'Could not resolve any eZ Network installation.';
    eZNetUtils::log( $message );
    $cli->output( $message );
    $script->shutdown();
    exit();
}

$cli->output( "Downloading a new version of the eZ Network extension.
The extension will be placed in the root directory of your eZ Publish installation." );

if( $installExtension )
{
    $cli->output( "As '--install' option is used so the new version will be installed automatically." );
}
$cli->output( "
Current installed version of the eZ Network extension is " . $eznetClientInfo->currentVersion() . ".
This may take few minutes. Please wait... " );

$ini = eZINI::instance( 'sync.ini' );
$Server = $ini->variable( 'NetworkSettings', 'Server' );
$Port = $ini->variable( 'NetworkSettings', 'Port' );
$Path = $ini->variable( 'NetworkSettings', 'Path' );

// If use of SSL fails the client must attempt to use HTTP
$Port = eZNetSOAPSync::getPort( $Server, $Path, $Port );

$request = new eZSOAPRequest( 'eZNetMonSOAPTools__downloadExtension', eZNetSOAPSync::SYNC_NAMESPACE );
$request->addParameter( 'installationKey', $installation->attribute( 'remote_id' ) );
$request->addParameter( 'nodeID', eZNetUtils::nodeID() );
$client = new eZSOAPClient( $Server, $Path, $Port );
$response = $client->send( $request );

if( !$response ||
    $response->isFault() )
{
    $message = 'Could not successfully connect to the eZ Network server: ' . $Server . ':' . $Port . $Path;

    if ( $response instanceof eZSOAPResponse )
        $message .= ' returned "' . $response->FaultString . '"';

    eZNetUtils::log( $message );
    $cli->output( $message );
    $script->shutdown();
    exit();
}

$resultArray = $response->value();
if ( !$resultArray['success'] )
{
    if ( array_key_exists( 'error_description', $resultArray ) )
    {
        $errorText = $resultArray['error_description'];
    }
    else
    {
        $errorText = 'Unknown error on extension download.';
    }
    $message = 'An error occured: ' . $errorText;
    eZNetUtils::log( $message );
    $cli->output( $message );
    $script->shutdown();
    exit();
}

switch( $resultArray['file_extension'] )
{
    case 'zip':
    {
        $filename = 'ez_network.zip';
    } break;

    case 'tar.gz':
    {
        $filename = 'ez_network.tar.gz';
    } break;
}

eZFile::create( $filename,
                false,
                base64_decode( $resultArray['extension_data'] ) );
$message = 'Download successfull. Stored: ' . $filename . "\n" .
'( Platform: ' . $resultArray['platform'] .
', branch: ' . $resultArray['branch_name'] .
', version: ' . $resultArray['extension_version'] . ' )';
eZNetUtils::log( str_replace( "\n", ', ', $message ) );
$cli->output( $message );

$message = '';
if ( $installExtension &&
     $resultArray['file_extension'] == 'tar.gz' )
{
    $untarCommand = 'tar zhxf ez_network.tar.gz';
    $output = array();
    exec( $untarCommand, $output );
    $message = 'Extension unpacked. ';
}
$message .= "\nRun 'php runcronjobs.php sync_network' to complete the update process.";
eZNetUtils::log( $message );
$cli->output( $message );

$script->shutdown();

?>
