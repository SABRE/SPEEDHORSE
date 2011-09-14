<?php
//
// Definition of eZTemplateTextElement class
//
// Created on: <01-Mar-2002 13:50:45 amos>
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
  \class eZTemplateTextElement eztemplatetextelement.php
  \ingroup eZTemplateElements
  \brief Represents a text element in the template tree.

 This class containst the text of a text element.
*/

class eZTemplateTextElement
{
    /*!
     Initializes the object with the text.
    */
    function eZTemplateTextElement( $text )
    {
        $this->Text = $text;
    }

    /*!
     Returns #text.
    */
    function name()
    {
        return "#text";
    }

    function serializeData()
    {
        return array( 'class_name' => 'eZTemplateTextElement',
                      'parameters' => array( 'text' ),
                      'variables' => array( 'text' => 'Text' ) );
    }

    /*!
     Appends the element text to $text.
    */
    function process( $tpl, &$text )
    {
        $text .= $this->Text;
    }

    /*!
     Returns a reference to the element text.
    */
    function &text()
    {
        return $this->Text;
    }

    /// The element text
    public $Text;
}

?>
