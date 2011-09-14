<?php
//
// Definition of eZForwardCompressionHandler class
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
  \class eZForwardCompressionHandler ezgzipcompressionhandler.php
  \brief Handles files compressed with gzip

  This class is a wrapper of the eZGZIPZLIBCompressionHandler and
  eZGZIPShellCompressionHandler classes.
*/

class eZForwardCompressionHandler extends eZCompressionHandler
{
    /*!
     See eZCompressionHandler::eZCompressionHandler
    */
    function eZForwardCompressionHandler( &$handler,
                                          $name, $identifier )
    {
        $this->ForwardHandler =& $handler;
        $this->eZCompressionHandler( $name, $identifier );
    }

    /*!
     \return the current handler which all requests are forwarded to.
    */
    function &forwardHandler()
    {
        return $this->ForwardHandler;
    }

    function doOpen( $filename, $mode )
    {
        return $this->ForwardHandler->doOpen( $filename, $mode );
    }

    function doClose()
    {
        return $this->ForwardHandler->doClose();
    }

    function doRead( $uncompressedLength = false )
    {
        return $this->ForwardHandler->doRead( $uncompressedLength );
    }

    function doWrite( $data, $uncompressedLength = false )
    {
        return $this->ForwardHandler->doWrite( $data, $uncompressedLength );
    }

    function doFlush()
    {
        return $this->ForwardHandler->doFlush();
    }

    function doSeek( $offset, $whence )
    {
        return $this->ForwardHandler->doSeek( $offset, $whence );
    }

    function doRewind()
    {
        return $this->ForwardHandler->doRewind();
    }

    function doTell()
    {
        return $this->ForwardHandler->doTell();
    }

    function doEOF()
    {
        return $this->ForwardHandler->doEOF();
    }

    function doPasstrough( $closeFile = true )
    {
        return $this->ForwardHandler->doPasstrough( $closeFile );
    }

    function compress( $source )
    {
        return $this->ForwardHandler->compress( $source );
    }

    function decompress( $source )
    {
        return $this->ForwardHandler->decompress( $source );
    }

    function error()
    {
        return $this->ForwardHandler->error();
    }

    function errorString()
    {
        return $this->ForwardHandler->errorString();
    }

    function errorNumber()
    {
        return $this->ForwardHandler->errorNumber();
    }

    /*!
     Duplicates the forward compression handler by calling duplicate() on the handler
     which gets the forwarded requests and then creates a new eZForwardCompressionHandler.
    */
    function duplicate()
    {
        $forwardCopy = $this->ForwardHandler->duplicate();
        $copy = new eZForwardCompressionHandler( $forwardCopy, $this->name(), $this->identifier() );
        return $copy;
    }
}

?>
