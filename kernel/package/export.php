<?php
//
// Created on: <21-Nov-2003 18:11:45 amos>
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

$packageName = $Params['PackageName'];

$package = eZPackage::fetch( $packageName );
if ( !$package )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$package->attribute( 'can_export' ) )
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );


$exportDirectory = eZPackage::temporaryExportPath();
$exportName = $package->exportName();
$exportPath = $exportDirectory . '/' . $exportName;
$exportPath = $package->exportToArchive( $exportPath );

//return $module->redirectToView( 'view', array( 'full', $package->attribute( 'name' ) ) );

$fileName = $exportPath;
if ( $fileName != "" and file_exists( $fileName ) )
{
    clearstatcache();
    $fileSize = filesize( $fileName );
    $mimeType =  'application/octet-stream';
    $originalFileName = $exportName;
    $contentLength = $fileSize;
    $fileOffset = false;
    $fileLength = false;
    if ( isset( $_SERVER['HTTP_RANGE'] ) )
    {
        $httpRange = trim( $_SERVER['HTTP_RANGE'] );
        if ( preg_match( "/^bytes=([0-9]+)-$/", $httpRange, $matches ) )
        {
            $fileOffset = $matches[1];
            header( "Content-Range: bytes $fileOffset-" . $fileSize - 1 . "/$fileSize" );
            header( "HTTP/1.1 206 Partial Content" );
            $contentLength -= $fileOffset;
        }
    }

    header( "Pragma: " );
    header( "Cache-Control: " );
    header( "Content-Length: $contentLength" );
    header( "Content-Type: $mimeType" );
    header( "X-Powered-By: eZ Publish" );
    header( "Content-disposition: attachment; filename=$originalFileName" );
    header( "Content-Transfer-Encoding: binary" );
    header( "Accept-Ranges: bytes" );

    $fh = fopen( $fileName, "rb" );
    if ( $fileOffset )
    {
        eZDebug::writeDebug( $fileOffset, "seeking to fileoffset" );
        fseek( $fh, $fileOffset );
    }

    ob_end_clean();
    fpassthru( $fh );
    fflush( $fh );
    fclose( $fh );
    unlink( $fileName );
    eZExecution::cleanExit();
}


?>
