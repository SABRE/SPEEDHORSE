<?php
//
// Definition of eZGZIPCompressionHandler class
//
// Created on: <13-Aug-2003 16:20:19 amos>
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
  \class eZGZIPCompressionHandler ezgzipcompressionhandler.php
  \brief Handles files compressed with gzip

  This class is a wrapper of the eZGZIPZLIBCompressionHandler and
  eZGZIPShellCompressionHandler classes.

  Duplication of this handler is done by the eZForwardCompressionHandler class.
*/

class eZGZIPCompressionHandler extends eZForwardCompressionHandler
{
    /*!
     See eZCompressionHandler::eZCompressionHandler and eZForwardCompressionHandler::eZForwardCompressionHandler.
    */
    function eZGZIPCompressionHandler()
    {
        if ( eZGZIPZLIBCompressionHandler::isAvailable() )
            $handler = new eZGZIPZLIBCompressionHandler();
        else if ( eZGZIPShellCompressionHandler::isAvailable() )
            $handler = new eZGZIPShellCompressionHandler();
        else
            $handler = new eZNoCompressionHandler();
        $this->eZForwardCompressionHandler( $handler,
                                            'GZIP', 'gzip' );
    }

    /*!
     Forwards the compression level to the current handler.
    */
    function setCompressionLevel( $level )
    {
        $handler =& $this->handler();
        if ( method_exists( $handler, 'setCompressionLevel' ) )
            $handler->setCompressionLevel( $level );
    }

    /*!
     Forwards the request for compression level to the current handler and returns the value.
    */
    function compressionLevel()
    {
        $handler =& $this->handler();
        if ( method_exists( $handler, 'compressionLevel' ) )
            return $handler->compressionLevel();
        return false;
    }
}

?>
