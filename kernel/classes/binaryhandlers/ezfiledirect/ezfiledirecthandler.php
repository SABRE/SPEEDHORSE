<?php
//
// Definition of eZFileDirectHandler class
//
// Created on: <30-Apr-2002 16:47:08 bf>
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

/*!
  \class eZFileDirectHandler ezfiledirecthandler.php
  \ingroup eZBinaryHandlers
  \brief Handles file downloading by passing an URL directly to the file.

*/
class eZFileDirectHandler extends eZBinaryFileHandler
{
    const HANDLER_ID = 'ezfiledirect';

    function eZFileDirectHandler()
    {
        $this->eZBinaryFileHandler( self::HANDLER_ID, "direct download", eZBinaryFileHandler::HANDLE_DOWNLOAD );
    }

    function handleFileDownload( $contentObject, $contentObjectAttribute, $type, $fileInfo )
    {
        return eZBinaryFileHandler::RESULT_OK;
    }

    /*!
     \return the direct download template suffix
    */
    function viewTemplate( $contentobjectAttribute )
    {
        $retValue = 'direct';
        return $retValue;
    }

}

?>
