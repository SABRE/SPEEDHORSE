<?php
//
// Definition of eZSOAPCodec class
//
// Created on: <03-Jan-2006 10:12:37 hovik>
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
  \class eZSOAPCodec ezsoapcodec.php
  \brief The class eZSOAPCodec does

*/

class eZSOAPCodec
{
    /*!
     Constructor
    */
    function eZSOAPCodec()
    {
    }

    /*!
      \static
      Encodes a PHP variable into a SOAP datatype.
    */
    static function encodeValue( $doc, $name, $value )
    {
        switch ( gettype( $value ) )
        {
            case "string" :
            {
                $node = $doc->createElement( $name );
                $node->appendChild( $doc->createTextNode( $value ) );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':string' );
                return $node;
            } break;

            case "boolean" :
            {
                $node = $doc->createElement( $name );
                $node->appendChild( $doc->createTextNode( $value ? 'true' : 'false' ) );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':boolean' );
                return $node;
            } break;

            case "integer" :
            {
                $node = $doc->createElement( $name );
                $node->appendChild( $doc->createTextNode( $value ) );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':int' );
                return $node;
            } break;

            case "double" :
            {
                $node = $doc->createElement( $name );
                $node->appendChild( $doc->createTextNode( $value ) );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':float' );
                return $node;
            } break;

            case "array" :
            {
                $arrayCount = count( $value );

                $isStruct = false;
                // Check for struct
                $i = 0;
                foreach( $value as $key => $val )
                {
                    if ( $i !== $key )
                    {
                        $isStruct = true;
                        break;
                    }
                    $i++;
                }

                if ( $isStruct == true )
                {
                    $node = $doc->createElement( $name );
                    $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                         eZSOAPEnvelope::ENC_PREFIX . ':SOAPStruct' );

                    foreach( $value as $key => $val )
                    {
                        $subNode = eZSOAPCodec::encodeValue( $doc, (string)$key, $val );
                        $node->appendChild( $subNode );
                    }
                    return $node;
                }
                else
                {
                    $node = $doc->createElement( $name );
                    $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                         eZSOAPEnvelope::ENC_PREFIX . ':Array' );
                    $node->setAttribute( eZSOAPEnvelope::ENC_PREFIX . ':arrayType',
                                         eZSOAPEnvelope::XSD_PREFIX . ":string[$arrayCount]" );

                    foreach ( $value as $arrayItem )
                    {
                        $subNode = eZSOAPCodec::encodeValue( $doc, "item", $arrayItem );
                        $node->appendChild( $subNode );
                    }

                    return  $node;
                }
            } break;
        }

        return false;
    }
}

?>
