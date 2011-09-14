<?php
//
// Definition of eZCompressionHandler class
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
  \class eZCompressionHandler ezcompressionhandler.php
  \brief Interface for file handlers using compression

  Generic interface for all file handlers using compression.

  This class introduces two new functions from the eZFileHandler base class,
  they are compress() and decompress() and are used for string based
  compression.

  <h1>Creating specific handlers</h1>

  The compressor handlers must inherit from this class and reimplement
  some virtual functions.

  For dealing with compressed strings the following functions must be reimplemented.
  compress() and decompress()

  The handlers must also implement the virtual functions defined in eZFileHandler.

*/

class eZCompressionHandler extends eZFileHandler
{
    /*!
     Initializes the handler. Optionally the parameters \a $filename
     and \a $mode may be provided to automatically open the file.
    */
    function eZCompressionHandler( $handlerIdentifier, $handlerName )
    {
        $this->eZFileHandler( $handlerIdentifier, $handlerName );
    }

    /*!
     \pure
     Compress the \a $source string and return it as compressed data.
    */
    function compress( $source )
    {
    }

    /*!
     \pure
     Decompress the \a $source string containing compressed data and return it as a string.
    */
    function decompress( $source )
    {
    }
}

?>
