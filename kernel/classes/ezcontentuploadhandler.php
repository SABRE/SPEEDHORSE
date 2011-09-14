<?php
//
// Definition of eZContentUploadHandler class
//
// Created on: <18-Mar-2005 18:14:02 amos>
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
  \class eZContentUploadHandler ezcontentuploadhandler.php
  \brief Interface for all content upload handlers

  This defines the interface for upload handlers for content objects. The handler
  will be used if upload.ini is configured for a specific MIME type.
  The uploading system is general and will be used both by Web uploads as well
  as WebDAV PUT requests.

  The handler must inherit this class and implement the following methods:
  - handleFile() - This takes care of creating content objects from the uploaded file.

  Also the constructor must pass a proper name and identifier to the eZContentUploadHandler.

*/

class eZContentUploadHandler
{
    /*!
     Initialises the handler with the name.
    */
    function eZContentUploadHandler( $name, $identifier )
    {
        $this->Name = $name;
        $this->Identifier = $identifier;
    }

    /*!
     \pure
     Handles the file \a $filePath and creates one ore more content objects.

     \param $upload The eZContentUpload object that instantiated the request, public methods on this can be used.
     \param $filePath Path to file which should be stored, do not remove or move this file.
     \param $mimeInfo Contains MIME-Type information on the file.
     \param $result Result data, will be filled with information which the client can examine, contains:
                    - errors - An array with errors, each element is an array with \c 'description' containing the text
     \param $location The node ID which the new object will be placed or the string \c 'auto' for automatic placement of type.
     \param $existingNode Pass a contentobjecttreenode object to let the uploading be done to an existing object,
                          if not it will create one from scratch.

     \return \c false if something failed or \c true if succesful.
     \note might be transaction unsafe.
    */
    function handleFile( &$upload, &$result,
                         $filePath, $originalFilename, $mimeInfo,
                         $location, $existingNode )
    {
        return false;
    }

    /// \privatesection
    /// The name of the handler, can be displayed to the end user.
    public $Name;
    /// The identifier of the handler.
    public $Identifier;
}

?>
