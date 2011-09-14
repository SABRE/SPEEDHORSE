<?php
//
// Definition of eZPDFParser class
//
// Created on: <16-Jun-2003 16:38:22 bf>
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
  \class eZPDFParser ezpdfparser.php
  \ingroup eZKernel
  \brief The class eZPDFParser handles parsing of PDF files and returns the metadata

*/

class eZPDFParser
{
    function parseFile( $fileName )
    {
        $binaryINI = eZINI::instance( 'binaryfile.ini' );

        $textExtractionTool = $binaryINI->variable( 'PDFHandlerSettings', 'TextExtractionTool' );

        // save the buffer contents
        $buffer = ob_get_contents();
        ob_end_clean();

        // fetch the module printout
        ob_start();
        passthru( "$textExtractionTool $fileName" );
        $metaData = ob_get_contents();
        ob_end_clean();

        // fill the buffer with the old values
        ob_start();
        print( $buffer );

        return $metaData;
    }
}

?>
