<?php
//
// Definition of eZGZIPShellCompressionHandler class
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
  \class eZGZIPShellCompressionHandler ezgzipshellcompressionhandler.php
  \brief Handles files compressed with gzip using the shell commands

  Handles GZIP compression by executing the 'gzip' executable,
  without this the handler cannot work.

NOTE: This is not done yet.
*/

class eZGZIPShellCompressionHandler extends eZCompressionHandler
{
    function eZGZIPShellCompressionHandler()
    {
        $this->File = false;
        $thus->Level = false;
        $this->eZCompressionHandler( 'GZIP (shell)', 'gzipshell' );
    }

    /*!
     Sets the current compression level.
    */
    function setCompressionLevel( $level )
    {
        if ( $level < 0 or $level > 9 )
            $level = false;
        $this->Level = $level;
    }

    /*!
     \return the current compression level which is a number between 0 and 9,
             or \c false if the default is to be used.
    */
    function compressionLevel()
    {
        return $this->Level;
    }

    /*!
     \return true if this handler can be used.
    */
    static function isAvailable()
    {
        return false;
    }

    function gunzipFile( $filename )
    {
        $command = 'gzip -dc $filename > $';
    }

    function doOpen( $filename, $mode )
    {
        $this->File = @gzopen( $filename, $mode );
    }

    function doClose()
    {
        return @gzclose( $this->File );
    }

    function doRead( $uncompressedLength = false )
    {
        return @gzread( $this->File, $uncompressedLength );
    }

    function doWrite( $data, $uncompressedLength = false )
    {
        return @gzwrite( $this->File, $uncompressedLength );
    }

    function doFlush()
    {
        return @fflush( $this->File );
    }

    function doSeek( $offset, $whence )
    {
        if ( $whence == SEEK_SET )
            $offset = $offset - gztell( $this->File );
        else if ( $whence == SEEK_END )
        {
            eZDebugSetting::writeError( 'lib-ezfile-gziplibz',
                                        "Seeking from end is not supported for gzipped files" );
            return false;
        }
        return @gzseek( $this->File, $offset );
    }

    function doRewind()
    {
        return @gzrewind( $this->File );
    }

    function doTell()
    {
        return @gztell( $this->File );
    }

    function doEOF()
    {
        return @gzeof( $this->File );
    }

    function doPasstrough( $closeFile = true )
    {
        return @gzpasstru( $this->File );
    }

    function compress( $source )
    {
        return @gzcompress( $source, $this->Level );
    }

    function decompress( $source )
    {
        return @gzuncompress( $source );
    }

    function errorString()
    {
        return false;
    }

    function errorNumber()
    {
        return false;
    }

    /// \privatesection
    /// File pointer, returned by gzopen
    public $File;
    /// The compression level
    public $Level;
}

?>
