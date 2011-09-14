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

    include_once( "extension/ezodf/modules/ezodf/ezooimport.php" );
    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
    include_once( "lib/ezutils/classes/ezhttptool.php" );
    include_once( "lib/ezutils/classes/ezhttpfile.php" );

    $http = eZHTTPTool::instance();

    if ( $http->hasPostVariable( 'Username' ) );
        $username = $http->postVariable( 'Username' );

    if ( $http->hasPostVariable( 'Password' ) );
        $password = $http->postVariable( 'Password' );

    if ( $http->hasPostVariable( 'NodeID' ) );
        $nodeID = $http->postVariable( 'NodeID' );

    if ( $http->hasPostVariable( 'ImportType' ) );
        $importType = $http->postVariable( 'ImportType' );

    // User authentication
    $user = eZUser::loginUser( $username, $password );
    if ( $user == false )
    {
        print( 'problem:Authentication failed' );
        eZExecution::cleanExit();
    }

    if ( !eZHTTPFile::canFetch( 'File' ) )
    {
        print( 'problem:Can\'t fetch HTTP file.' );
        eZExecution::cleanExit();
    }

    $file = eZHTTPFile::fetch('File');

    $fileName = $file->attribute( 'filename' );
    $originalFilename = $file->attribute('original_filename');

    $content = base64_decode( file_get_contents( $fileName ) );

    $fd = fopen( $fileName, 'w' );
    fwrite( $fd, $content );
    fclose( $fd );

    // Conversion of the stored file
    $import = new eZOOImport();
    $importResult = $import->import( $fileName, $nodeID, $originalFilename, $importType );

    // Verification : conversion OK ?
    if ( $import->getErrorNumber( ) != 0 )
    {
        print( 'problem:Import : ' . $import->getErrorMessage( ) );
        eZExecution::cleanExit();
    }

    // End : print return string
    print( 'done:File successfully exported with nodeID ' . $importResult['MainNode']->attribute('node_id') );

    // Don't display eZ Publish page structure
    eZExecution::cleanExit();

?>
