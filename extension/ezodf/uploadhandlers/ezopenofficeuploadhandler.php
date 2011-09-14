<?php
//
// Definition of eZOpenofficehandler class
//
// Created on: <27-Jul-2005 14:52:11 bf>
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

/*! \file ezopenofficehandler.php
*/

/*!
  \class eZOpenofficehandler ezopenofficehandler.php
  \brief The class eZOpenofficehandler handles uploads of ODF files

*/

class eZOpenofficeUploadHandler extends eZContentUploadHandler
{
    function eZOpenofficeUploadHandler()
    {
        $this->eZContentUploadHandler( 'OOo file handling', 'openoffice' );
    }

    /*!
      Handling the uploading of OpenOffice.org docuemnt.
    */
    function handleFile( &$upload, &$result,
                         $filePath, $originalFilename, $mimeinfo,
                         $location, $existingNode )
    {
        $tmpDir = getcwd() . "/" . eZSys::cacheDirectory();

        $originalFilename = basename( $originalFilename );
        $tmpFile = $tmpDir . "/" . $originalFilename;
        copy( $filePath, $tmpFile );

        $import = new eZOOImport();
        $tmpResult = $import->import( $tmpFile, $location, $originalFilename, 'import', $upload );

        $result['contentobject'] = $tmpResult['Object'];
        $result['contentobject_main_node'] = $tmpResult['MainNode'];
        unlink( $tmpFile );


        return true;
    }

}
?>
