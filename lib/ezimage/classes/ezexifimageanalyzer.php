<?php
//
// Definition of eZEXIFImageAnalyzer class
//
// Created on: <03-Nov-2003 15:19:16 amos>
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
  \class eZEXIFImageAnalyzer ezexifimageanalyzer.php
  \ingroup eZImageAnalyzer
  \brief Analyzes image formats which can contain EXIF information.

*/

class eZEXIFImageAnalyzer
{
    /*!
     Constructor
    */
    function eZEXIFImageAnalyzer()
    {
    }

    /*!
     Checks the file for EXIF data and returns the information.
    */
    function process( $mimeData, $parameters = array() )
    {
        $printInfo = false;
        if ( isset( $parameters['print_info'] ) )
            $printInfo = $parameters['print_info'];

        $filename = $mimeData['url'];
        if ( file_exists( $filename ) )
        {
            if ( function_exists( 'exif_read_data' ) )
            {
                $exifData = exif_read_data( $filename, "COMPUTED,IFD0,COMMENT,EXIF", true );
                if ( $exifData )
                {
                    $info = array();
                    if ( isset( $exifData['COMPUTED'] ) )
                    {
                        foreach ( $exifData['COMPUTED'] as $key => $item )
                        {
                            if ( strtolower( $key ) == 'html' )
                                continue;
                            $info[$key] = $exifData['COMPUTED'][$key];
                        }
                    }
                    if ( isset( $exifData['IFD0'] ) )
                    {
                        $info['ifd0'] = $exifData['IFD0'];
                    }
                    if ( isset( $exifData['EXIF'] ) )
                    {
                        $info['exif'] = $exifData['EXIF'];
                    }
                    return $info;
                }
            }
        }
        return false;
    }

}

?>
