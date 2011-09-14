<?php
//
// Definition of siblocksupdate cronjob
//
// Created on: <28-Apr-2008 10:06:19 jr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
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

class eZSIFSFileHandler extends eZSIFileHandler
{
    private function eZSIFSFileHandler()
    {
    }

    public static function instance()
    {
        return new eZSIFSFileHandler;
    }

    public function storeFile( $directory, $fileName, $fileContents )
    {
        return eZFile::create( $fileName, 'var/' . $directory, $fileContents );
    }

    public function removeFile( $directory, $fileName )
    {
        eZDebug::writeNotice( 'Removing ' . $directory . '/' . $fileName, __METHOD__ );
        return @unlink( $directory . '/' . $fileName );
    }
}
?>
