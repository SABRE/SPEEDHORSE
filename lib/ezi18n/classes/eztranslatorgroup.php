<?php
//
// Definition of eZTranslatorGroup class
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
  \class eZTranslatorGroup eztranslatorgroup.php
  \ingroup eZTranslation
  \brief Allows for picking translator handlers according to context

*/

class eZTranslatorGroup extends eZTranslatorHandler
{
    /*!
     Constructor
    */
    function eZTranslatorGroup( $is_key_based )
    {
        $this->eZTranslatorHandler( $is_key_based );
        $this->Handlers = array();
    }

    /*!
     \pure
     \return the translation message for the key \a $key or null if the key does not exist.

     This function must overridden if isKeyBased() is true.
    */
    function findKey( $key )
    {
        $num = $this->keyPick( $key );
        if ( $num >=0 and $num <= count( $this->Handlers ) )
        {
            $handler = $this->Handlers[$num];
            return $handler->findKey( $key );
        }
        $retValue = null;
        return $retValue;
    }

    /*!
     \pure
     \return the translation message for \a $source and \a $context or null if the key does not exist.

     If you know the translation key use findKey() instead.

     This function must overridden if isKeyBased() is true.
    */
    function findMessage( $context, $source, $comment = null )
    {
        $num = $this->pick( $context, $source, $comment );
        if ( $num >=0 and $num <= count( $this->Handlers ) )
        {
            $handler = $this->Handlers[$num];
            return $handler->findMessage( $context, $source, $comment );
        }
        $retValue = null;
        return $retValue;
    }

    /*!
     \pure
     \return the translation string for \a $source and \a $context or null if the translation does not exist.

     \sa findMessage, findKey
    */
    function translate( $context, $source, $comment = null )
    {
        $num = $this->pick( $context, $source, $comment );
        if ( $num >=0 and $num <= count( $this->Handlers ) )
        {
            $handler = $this->Handlers[$num];
            return $handler->translate( $context, $source, $comment );
        }

        return null;
    }

    /*!
     \pure
     \return the translation string for \a $key or null if the translation does not exist.

     \sa findMessage, findKey
    */
    function keyTranslate( $key )
    {
        $num = $this->keyPick( $key );
        if ( $num >=0 and $num <= count( $this->Handlers ) )
        {
            $handler = $this->Handlers[$num];
            return $handler->keyTranslate( $key );
        }

        return null;
    }

    /*!
     \pure
     Reimplement this to pick one of the registered handlers based on \a $key.
     \return -1 for no handler or a number within the handler range (starting from 0).
     \sa pick
    */
    function keyPick( $key )
    {
    }

    /*!
     \pure
     Reimplement this to pick one of the registered handlers based on \a $context, \a $source and \a $comment.
     \return -1 for no handler or a number within the handler range (starting from 0).
     \sa keyPick
    */
    function pick( $context, $source, $comment )
    {
    }

    /*!
     \return the number of registered handlers.
    */
    function handlerCount()
    {
        return count( $this->Handlers );
    }

    /*!
     Registers the handler object \a $handler.
    */
    function registerHandler( $handler )
    {
        if ( !$this->isKeyBased() and $handler->isKeyBased() )
        {
            eZDebug::writeError( "Cannot register keybased handler for non-keybased group", "eZTranslatorGroup" );
            return false;
        }
        $this->Handlers[] = $handler;
        return true;
    }

    /// \privatesection
    /// The array of grouped handlers
    public $Handlers;
}

?>
