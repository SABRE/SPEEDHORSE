<?php
//
// Definition of eZTemplateSectionIterator class
//
// Created on: <26-Feb-2004 11:33:05 >
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
  \class eZTemplateSectionIterator eztemplatesectioniterator.php
  \ingroup eZTemplateFunctions
  \brief The iterator item in a section loop which works as a proxy.

  The iterator provides transparent access to iterator items. It will
  redirect all attribute calls to the iterator item with the exception
  of a few internal values. The internal values are
  - item - The actual item, provides backwards compatibility
  - key - The current key
  - index - The current index value (starts at 0 and increases with 1 for each element)
  - number - The current index value + 1 (starts at 1 and increases with 1 for each element)
  - sequence - The current sequence value
  - last - The last iterated element item
*/

class eZTemplateSectionIterator
{
    /*!
     Initializes the iterator with empty values.
    */
    function eZTemplateSectionIterator()
    {
        $this->InternalAttributes = array( 'item' => false,
                                           'key' => false,
                                           'index' => false,
                                           'number' => false,
                                           'sequence' => false,
                                           'last' => false );
        $this->InternalAttributeNames = array_keys( $this->InternalAttributes );
    }

    /*!
     \return the value of the current item for the template system to use.
    */
    function templateValue()
    {
        $value = $this->InternalAttributes['item'];
        return $value;
    }

    /*!
     \return a merged list of attributes from both the internal attributes and the items attributes.
    */
    function attributes()
    {
        $attributes = array();
        $item = $this->InternalAttributes['item'];
        if ( is_array( $item ) )
        {
            $attributes = array_keys( $item );
        }
        else if ( is_object( $item ) and
                  method_exists( $item, 'attributes' ) )
        {
            $attributes = $item->attributes();
        }
        $attributes = array_merge( $this->InternalAttributes, $attributes );
        $attributes = array_unique( $attributes );
        return $attributes;
    }

    /*!
     \return \c true if the attribute \a $name exists either in
             the internal attributes or in the item value.
    */
    function hasAttribute( $name )
    {
        if ( in_array( $name, $this->InternalAttributeNames ) )
            return true;
        $item = $this->InternalAttributes['item'];
        if ( is_array( $item ) )
        {
            return in_array( $name, array_keys( $item ) );
        }
        else if ( is_object( $item ) and
                  method_exists( $item, 'hasAttribute' ) )
        {
            return $item->hasAttribute( $name );
        }
        return false;
    }

    /*!
     \return the attribute value of either the internal attributes or
             from the item value if the attribute exists for it.
    */
    function attribute( $name )
    {
        if ( in_array( $name, $this->InternalAttributeNames ) )
        {
            return $this->InternalAttributes[$name];
        }
        $item = $this->InternalAttributes['item'];
        if ( is_array( $item ) )
        {
            return $item[$name];
        }
        else if ( is_object( $item ) and
                  method_exists( $item, 'attribute' ) )
        {
            return $item->attribute( $name );
        }
        eZDebug::writeError( "Attribute '$name' does not exist", __METHOD__ );
        return null;
    }

    /*!
     Updates the iterator with the current iteration values.
    */
    function setIteratorValues( $item, $key, $index, $number, $sequence, &$last )
    {
        $this->InternalAttributes['item'] = $item;
        $this->InternalAttributes['key'] = $key;
        $this->InternalAttributes['index'] = $index;
        $this->InternalAttributes['number'] = $number;
        $this->InternalAttributes['sequence'] = $sequence;
        $this->InternalAttributes['last'] = $last;
    }

    /*!
     Updates the current sequence value to \a $sequence.
    */
    function setSequence( $sequence )
    {
        $this->InternalAttributes['sequence'] = $sequence;
    }
}

?>
