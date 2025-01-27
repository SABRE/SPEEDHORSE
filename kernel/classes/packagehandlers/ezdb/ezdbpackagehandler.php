<?php
//
// Definition of eZDBPackageHandler class
//
// Created on: <23-Jul-2003 16:11:42 amos>
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

/*! \file
*/

/*!
  \class eZDBPackageHandler ezdbpackagehandler.php
  \brief Handles SQL files in the package system

*/

class eZDBPackageHandler extends eZPackageHandler
{
    /*!
     Constructor
    */
    function eZDBPackageHandler()
    {
        $this->eZPackageHandler( 'ezdb' );
    }

    /*!
     Installs the package type
    */
    function install( $package, $installType, $parameters,
                      $name, $os, $filename, $subdirectory,
                      $content, &$installParameters,
                      &$installData )
    {
        $path = $package->path();
        $databaseType = false;
        if ( isset( $parameters['database-type'] ) )
        {
            $databaseType = $parameters['database-type'];
        }
        $path .= '/' . eZDBPackageHandler::sqlDirectory();
        if ( $databaseType )
        {
            $path .= '/' . $databaseType;
        }

        if ( file_exists( $path ) )
        {
            $db = eZDB::instance();
            $canInsert = true;
            if ( $databaseType and
                 $databaseType != $db->databaseName() )
            {
                $canInsert = false;
            }

            if ( $canInsert )
            {
                eZDebug::writeDebug( "Installing SQL file $path/$filename" );
                $db->insertFile( $path, $filename, false );
                return true;
            }
            else
            {
                eZDebug::writeDebug( "Skipping SQL file $path/$filename" );
            }
        }
        else
        {
            eZDebug::writeError( "Could not find SQL file $path/$filename" );
        }
        return false;
    }

    function add( $packageType, $package, $cli, $parameters )
    {
        if ( isset( $parameters['sql-file-list'] ) )
        {
            foreach ( $parameters['sql-file-list'] as $fileItem )
            {
                $package->appendInstall( 'sql', false, false, true,
                                         $fileItem['file'], false,
                                         array( 'path' => $fileItem['path'],
                                                'database-type' => $fileItem['database_type'],
                                                'copy-file' => true ) );
                if ( $fileItem['database_type'] )
                    $package->appendDependency( 'requires',
                                                array( 'type' => 'ezdb',
                                                       'name' => $fileItem['database_type'],
                                                       'value' => false ) );
                $noticeText = "Adding " . $cli->stylize( 'mark', "sql" ) . " file " . $cli->stylize( 'file', $fileItem['path'] );
                if ( $fileItem['database_type'] )
                    $noticeText .= " for database " . $cli->stylize( 'emphasize',  $fileItem['database_type']  );
                $cli->notice( $noticeText );
            }
        }
    }

    function handleAddParameters( $packageType, $package, $cli, $arguments )
    {
        return $this->handleParameters( $packageType, $package, $cli, 'add', $arguments );
    }

    function handleParameters( $packageType, $package, $cli, $type, $arguments )
    {
        $sqlFileList = array();
        $currentDatabaseType = false;

        for ( $i = 0; $i < count( $arguments ); ++$i )
        {
            $argument = $arguments[$i];
            if ( $argument[0] == '-' )
            {
                if ( strlen( $argument ) > 1 and
                     $argument[1] == '-' )
                {
                }
                else
                {
                    $flag = substr( $argument, 1, 1 );
                    if ( $flag == 'd' )
                    {
                        if ( strlen( $argument ) > 2 )
                        {
                            $data = substr( $argument, 2 );
                        }
                        else
                        {
                            $data = $arguments[$i+1];
                            ++$i;
                        }
                        if ( $flag == 'd' )
                        {
                            $currentDatabaseType = $data;
                        }
                    }
                }
            }
            else
            {
                $sqlFile = $argument;
                $databaseType = $currentDatabaseType;
                $realFilePath = $this->sqlFileExists( $sqlFile, $databaseType,
                                                      $triedFiles );
                if ( !$realFilePath )
                {
                    $cli->error( "SQL file " . $cli->style( 'file' ) . $sqlFile . $cli->style( 'file-end' ) . " does not exist\n" .
                                 "The following files were searched for:\n" .
                                 implode( "\n", $triedFiles ) );
                    return false;
                }
                $fileList[] = array( 'file' => $sqlFile,
                                     'database_type' => $databaseType,
                                     'path' => $realFilePath );
            }
        }
        if ( count( $fileList ) == 0 )
        {
            $cli->error( "No files were added" );
            return false;
        }
        return array( 'sql-file-list' => $fileList );
    }

    function sqlFileExists( &$sqlFile, &$databaseType,
                            &$triedFiles )
    {
        $triedFiles = array();
        if ( file_exists( $sqlFile ) )
        {
            $filePath = $sqlFile;
            if ( preg_match( '#^.+/([^/]+$)#', $sqlFile, $matches ) )
                $sqlFile = $matches[1];
            return $filePath;
        }
        $filePath = 'kernel/sql/' . $databaseType . '/' . $sqlFile;
        if ( file_exists( $filePath ) )
            return $filePath;
        $triedFiles[] = $filePath;
        $filePath = 'kernel/sql/' . $databaseType . '/' . $sqlFile . '.sql';
        if ( file_exists( $filePath ) )
        {
            $sqlFile .= '.sql';
            return $filePath;
        }
        $triedFiles[] = $filePath;
        return false;
    }

    function sqlDirectory()
    {
        return 'sql';
    }

    function createInstallNode( $package, $installNode, $installItem, $installType )
    {
        $installNode->setAttribute( 'original-path', $installItem['path'] );
        $installNode->setAttribute( 'database-type', $installItem['database-type'] );

        $originalPath = $installItem['path'];
        $installDirectory = $package->path() . '/' . eZDBPackageHandler::sqlDirectory();
        if ( $installItem['database-type'] )
            $installDirectory .= '/' . $installItem['database-type'];
        if ( !file_exists(  $installDirectory ) )
            eZDir::mkdir( $installDirectory, false, true );
        eZFileHandler::copy( $originalPath, $installDirectory . '/' . $installItem['filename'] );
    }

    function parseInstallNode( $package, $installNode, &$installParameters, $isInstall )
    {
        $installParameters['path'] = $installNode->getAttribute( 'original-path' );
        $installParameters['database-type'] = $installNode->getAttribute( 'database-type' );
    }
}

?>
