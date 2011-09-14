#!/usr/bin/env php
<?php
//
// Created on: <30-Mar-2006 06:30:00 vs>
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

/*

NOTE:

 Please read doc/features/3.8/clustering.txt and set up clustering
 before running this script.

*/

error_reporting( E_ALL | E_NOTICE );

require 'autoload.php';

// This code is taken from eZBinaryFile::storedFileInfo()
function filePathForBinaryFile($fileName, $mimeType )
{
    $storageDir = eZSys::storageDirectory();
    list( $group, $type ) = explode( '/', $mimeType );
    $filePath = $storageDir . '/original/' . $group . '/' . $fileName;
    return $filePath;
}

function copyBinaryfilesToDB( $remove )
{
    global $cli, $fileHandler;

    $db = eZDB::instance();

    $cli->output( "Importing binary files to database:");
    $rows = $db->arrayQuery('select filename, mime_type from ezbinaryfile' );

    foreach( $rows as $row )
    {
        $filePath = filePathForBinaryFile( $row['filename'] , $row['mime_type'] );
        $cli->output( "- " . $filePath);
        $fileHandler->fileStore( $filePath, 'binaryfile', $remove );
    }

    $cli->output();
}

function copyMediafilesToDB( $remove )
{
    global $cli, $fileHandler;

    $db = eZDB::instance();

    $cli->output( "Importing media files to database:");
    $rows = $db->arrayQuery('select filename, mime_type from ezmedia' );
    foreach( $rows as $row )
    {
        $filePath = filePathForBinaryFile( $row['filename'] , $row['mime_type'] );
        $cli->output( "- " . $filePath);
        $fileHandler->fileStore( $filePath, 'mediafile', $remove );
    }

    $cli->output();
}

function copyImagesToDB( $remove )
{
    global $cli, $fileHandler;

    $db = eZDB::instance();

    $cli->output( "Importing images and imagealiases files to database:");
    $rows = $db->arrayQuery('select filepath from ezimagefile' );
    foreach( $rows as $row )
    {
        $filePath = $row['filepath'];
        $cli->output( "- " . $filePath);

        $mimeData = eZMimeType::findByFileContents( $filePath );
        $fileHandler->fileStore( $filePath, 'image', $remove, $mimeData['name'] );
    }
}

function copyFilesFromDB( $excludeScopes, $remove )
{
    global $cli, $fileHandler;

    $cli->output( "Exporting files from database:");
    $filePathList = $fileHandler->getFileList( $excludeScopes, true );

    foreach ( $filePathList as $filePath )
    {
        $cli->output( "- " . $filePath );
        eZDir::mkdir( dirname( $filePath ), false, true );
        $fileHandler->fileFetch( $filePath );

        if ( $remove )
            $fileHandler->fileDelete( $filePath );
    }

    $cli->output();
}

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish (un)clusterize\n" .
                                                        "Script for moving var_dir files from " .
                                                        "filesystem to database and vice versa\n" .
                                                        "\n" .
                                                        "./bin/php/clusterize.php" ),
                                     'use-session'    => false,
                                     'use-modules'    => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[u][skip-binary-files][skip-media-files][skip-images][r][n]",
                                "",
                                array( 'u'                 => 'Unclusterize',
                                       'skip-binary-files' => 'Skip copying binary files',
                                       'skip-media-files'  => 'Skip copying media files',
                                       'skip-images'       => 'Skip copying images',
                                       'r'                 => 'Remove files after copying',
                                       'n'                 => 'Do not wait' ) );

$script->initialize();

$clusterize = !isset( $options['u'] );
$remove     =  isset( $options['r'] );
$copyFiles  = !isset( $options['skip-binary-files'] );
$copyMedia  = !isset( $options['skip-media-files'] );
$copyImages = !isset( $options['skip-images'] );
$wait       = !isset( $options['n'] );

if ( $wait )
{
    $warningMsg = sprintf( "This script will now %s your files and/or images %s database.",
                           ( $remove ? "move" : "copy" ),
                           ( $clusterize ? 'to' : 'from' ) );
    $cli->warning( $warningMsg );
    $cli->warning( "You have 10 seconds to break the script (press Ctrl-C)." );
    sleep( 10 );
}

$fileHandler = eZClusterFileHandler::instance();
if ( !is_object( $fileHandler ) )
{
    $cli->error( "Clustering settings specified incorrectly or the chosen file handler is ezfs." );
    $script->shutdown( 1 );
}
// the script will only run if clusterizing is supported by the currently
// configured handler
elseif ( !$fileHandler->requiresClusterizing() )
{
    $message = "The current cluster handler (" . get_class( $fileHandler ) . ") " .
               "doesn't require/support running this script";
    $cli->output( $message );
    $script->shutdown( 0 );
}

// clusterize, from FS => cluster
if ( $clusterize )
{
    if ( $copyFiles )
        copyBinaryfilesToDB( $remove );
    if ( $copyImages )
        copyImagesToDB( $remove );
    if ( $copyMedia )
        copyMediafilesToDB( $remove );
}
// unclusterize, from cluster => FS
else
{
    $excludeScopes = array();
    if ( !$copyFiles )
        $excludeScopes[] = 'binaryfile';
    if ( !$copyImages )
        $excludeScopes[] = 'image';
    if ( !$copyMedia )
        $excludeScopes[] = 'mediafile';

    copyFilesFromDB( $excludeScopes, $remove );
}

$script->shutdown();
?>
