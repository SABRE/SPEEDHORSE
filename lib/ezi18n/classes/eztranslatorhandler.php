<?php
//
// Definition of eZTranslatorHandler class
//
// Created on: <10-Jun-2002 11:05:00 amos>
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
  \class eZTranslatorHandler eztranslatorhandler.php
  \ingroup eZTranslation
  \brief Base class for translation handling

*/

class eZTranslatorHandler
{
    /*!
     Constructor
    */
    function eZTranslatorHandler( $is_key_based )
    {
        $this->IsKeyBased = $is_key_based;
    }

    /*!
     \return true if the handler can lookup translations with translation keys.

    */
    function isKeyBased()
    {
        return $this->IsKeyBased;
    }

    /*!
     \pure
     \return the translation message for the key \a $key or null if the key does not exist.

     This function must overridden if isKeyBased() is true.
    */
    function findKey( $key )
    {
        return null;
    }

    /*!
     \pure
     \return the translation message for \a $source and \a $context or null if the key does not exist.

     If you know the translation key use findKey() instead.

     This function must overridden if isKeyBased() is true.
    */
    function findMessage( $context, $source, $comment = null )
    {
        return null;
    }

    /*!
     \pure
     \return the translation string for \a $source and \a $context or null if the translation does not exist.

     \sa findMessage, findKey
    */
    function translate( $context, $source, $comment = null )
    {
        return null;
    }

    /*!
     \pure
     \return the translation string for \a $key or null if the translation does not exist.

     \sa findMessage, findKey
    */
    function keyTranslate( $key )
    {
        return null;
    }

    /// \privatesection
    /// Tells whether the handler is key based or not
    public $IsKeyBased;
}

?>
