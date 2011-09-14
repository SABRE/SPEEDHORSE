#!/usr/bin/env php
<?php
//
// Created on: <27-Jul-2007 09:29:16 bjorn>
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

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish datatype sql update\n\n" .
                                                        "Script can be run as:\n" .
                                                        "bin/php/ezimportdbafile.php --datatype=\n\n" .
                                                        "Example: bin/php/ezimportdbafile.php --datatype=ezisbn" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[datatype:]", "",
                                array( 'datatype' => "The name of the datatype where the database should be updated." ) );
$script->initialize();
$dataTypeName = $options['datatype'];

if ( $dataTypeName === null )
{
    $cli->output( "Error: The option --datatype is required. Add --help for more information." );
}

$allowedDatatypes = eZDataType::allowedTypes();
if ( $dataTypeName !== null and
     in_array( $dataTypeName, $allowedDatatypes ) )
{
    // Inserting data from the dba-data files of the datatypes
    eZDataType::loadAndRegisterAllTypes();
    $registeredDataTypes = eZDataType::registeredDataTypes();

    if ( isset( $registeredDataTypes[$dataTypeName] ) )
    {
        $dataType = $registeredDataTypes[$dataTypeName];
        if ( $dataType->importDBDataFromDBAFile() )
        {
            $cli->output( "The database is updated for the datatype: " .
                          $cli->style( 'emphasize' ) . $dataType->DataTypeString . $cli->style( 'emphasize-end' ) . "\n" .
                          'dba-data is imported from the file: ' .
                          $cli->style( 'emphasize' ) . $dataType->getDBAFilePath() .  $cli->style( 'emphasize-end' ) );
        }
        else
        {
            $activeExtensions = eZExtension::activeExtensions();
            $errorString = "Failed importing datatype related data into database: \n" .
                           '  datatype - ' . $dataType->DataTypeString . ", \n" .
                           '  checked dba-data file - ' . $dataType->getDBAFilePath( false );
            foreach ( $activeExtensions as $activeExtension )
            {
                $fileName = eZExtension::baseDirectory() . '/' . $activeExtension .
                            '/datatypes/' . $dataType->DataTypeString . '/' . $dataType->getDBAFileName();
                $errorString .= "\n" . str_repeat( ' ', 23 ) . ' - ' . $fileName;
                if ( file_exists( $fileName ) )
                {
                    $errorString .= " (found, but not successfully imported)";
                }
            }

            $cli->error( $errorString );
        }
    }
    else
    {
        $cli->error( "Error: The datatype " . $dataTypeName . " does not exist." );
    }
}
else
{
    $cli->error( "Error: The datatype " . $dataTypeName . " is not registered." );
}
$script->shutdown();

?>
