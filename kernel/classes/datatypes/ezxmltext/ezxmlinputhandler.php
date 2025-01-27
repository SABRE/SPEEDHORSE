<?php
//
// Definition of eZXMLInputHandler class
//
// Created on: <06-Nov-2002 15:10:02 wy>
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
  \class eZXMLInputHandler ezxmlinputhandler.php
  \ingroup eZDatatype
  \brief The class eZXMLInputHandler does

*/

class eZXMLInputHandler
{
    /*!
     Constructor
    */
    function eZXMLInputHandler( $xmlData, $aliasedType, $contentObjectAttribute )
    {
        $this->XMLData = preg_replace( '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $xmlData, -1, $count );
        if ( $count > 0 )
        {
            eZDebug::writeWarning( "$count invalid character(s) detected. They have been removed from input.", __METHOD__ );
        }
        $this->ContentObjectAttribute = $contentObjectAttribute;
        $this->AliasedHandler = null;
        // use of $aliasedType is deprecated as of 4.1 and setting is ignored  in aliased_handler
        $this->AliasedType = $aliasedType;
    }

    /*!
     \return an array with attribute names.
    */
    function attributes()
    {
        return array( 'input_xml',
                      'aliased_type',
                      'aliased_handler',
                      'edit_template_name',
                      'information_template_name' );
    }

    /*!
     \return true if the attribute \a $name exists.
    */
    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    /*!
     \return the value of the attribute \a $name if it exists, if not returns \c null.
    */
    function attribute( $name )
    {
        switch ( $name )
        {
            case 'input_xml':
            {
                return $this->inputXML();
            } break;
            case 'edit_template_name':
            {
                return $this->editTemplateName();
            }break;
            case 'information_template_name':
            {
                return $this->informationTemplateName();
            }break;
            case 'aliased_type':
            {
                eZDebug::writeWarning( "'aliased_type' is deprecated as of 4.1 and not in use anymore, meaning it will always return false.", __METHOD__ );
                return $this->AliasedType;
            }break;
            case 'aliased_handler':
            {
                if ( $this->AliasedHandler === null )
                {
                    $this->AliasedHandler = eZXMLText::inputHandler( $this->XMLData,
                                                                     $this->AliasedType,
                                                                     false,
                                                                     $this->ContentObjectAttribute );
                }
                return $this->AliasedHandler;
            }break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", __METHOD__ );
                return null;
            }break;
        }
    }

    /*!
     \return the template name for this input handler, includes the edit suffix if any.
    */
    function editTemplateName()
    {
        $name = 'ezxmltext';
        $suffix = $this->editTemplateSuffix( $this->ContentObjectAttribute );
        if ( $suffix !== false )
        {
            $name .= '_' . $suffix;
        }
        return $name;
    }

    /*!
     \return the template name for this input handler, includes the information suffix if any.
    */
    function informationTemplateName()
    {
        $name = 'ezxmltext';
        $suffix = $this->informationTemplateSuffix( $this->ContentObjectAttribute );
        if ( $suffix !== false )
        {
            $name .= '_' . $suffix;
        }
        return $name;
    }

    /*!
     \pure
     Handles custom actions for input handler.
     \note Default does nothing, reimplement to check actions.
    */
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute )
    {
    }

    /*!
     \virtual
     \return true if the input handler is considered valid, if not the handler will not be used.
     \note Default returns true
    */
    function isValid()
    {
        return true;
    }

    /*!
     \pure
     \return the suffix for the attribute template, if false it is ignored.
    */
    function editTemplateSuffix( &$contentobjectAttribute )
    {
        return false;
    }

    /*!
     \pure
     \return the suffix for the attribute template, if false it is ignored.
    */
    function informationTemplateSuffix( &$contentobjectAttribute )
    {
        return false;
    }

    /*!
     \return the xml data as text.
    */
    function xmlData()
    {
        return $this->XMLData;
    }

    /*!
     \pure
     Validates user input and returns whether it can be used or not.
    */
    function validateInput( $http, $base, $contentObjectAttribute )
    {
        return eZInputValidator::STATE_INVALID;
    }

    /*!
     \pure
     Converts text input \a $text into an XML structure and returns it.
     \return an array where index 0 is the xml structure and index 1 is a message.
    */
    function convertInput( $text )
    {
        return null;
    }

    /*!
     \pure
     Returns the text representation of the XML structure, implement this to turn
     XML back into user input.
    */
    function inputXML()
    {
        return null;
    }

    /// \privatesection
    /// Contains the XML data as text
    public $XMLData;
    public $AliasedType;
    public $AliasedHandler;
    public $ContentObjectAttribute;
}

?>
