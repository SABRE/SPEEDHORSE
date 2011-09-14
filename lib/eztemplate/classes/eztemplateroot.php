<?php
//
// Definition of eZTemplateRoot class
//
// Created on: <01-Mar-2002 13:50:20 amos>
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

/*! \defgroup eZTemplateElements Template elements
    \ingroup eZTemplate
*/

/*!
  \class eZTemplateRoot eztemplateroot.php
  \ingroup eZTemplateElements
  \brief Represents a root element of the template tree.

  This starts the template tree and is the base of template includes.

  It has a list of child elements and runs process() on each child.
*/

class eZTemplateRoot
{
    /*!
     Initializes the object.
    */
    function eZTemplateRoot( $children = array() )
    {
        $this->Children = $children;
    }

    /*!
     Returns #root as the name.
    */
    function name()
    {
        return "#root";
    }

    function serializeData()
    {
        return array( 'class_name' => 'eZTemplateRoot',
                      'parameters' => array( 'children' ),
                      'variables' => array( 'children' => 'Children' ) );
    }

    /*!
     Runs process() on all child elements.
    */
    function process( $tpl, &$text, $nspace, $current_nspace )
    {
        foreach( array_keys( $this->Children ) as $key )
        {
            $this->Children[$key]->process( $tpl, $text, $nspace, $current_nspace );
        }
    }

    /*!
     Removes all children.
    */
    function clear()
    {
        $this->Children = array();
    }

    /*!
     Returns a reference to the child array.
    */
    function &children()
    {
        return $this->Children;
    }

    /*!
     Appends the child $node to the child array.
    */
    function appendChild( &$node )
    {
        $this->Children[] =& $node;
    }

    /// The child array
    public $Children = array();
}

?>
