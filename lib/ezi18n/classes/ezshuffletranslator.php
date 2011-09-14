<?php
//
// Definition of eZShuffleTranslator class
//
// Created on: <07-Jun-2002 12:40:42 amos>
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
  \class eZShuffleTranslator ez1337translator.php
  \ingroup eZTranslation
  \brief Translates text by moving characters around

*/

class eZShuffleTranslator extends eZTranslatorHandler
{
    /*!
     Construct the translator and loads the translation file $file if is set and exists.
    */
    function eZShuffleTranslator( $max_chars = 3 )
    {
        $this->eZTranslatorHandler( false );

        $this->MaxChars = $max_chars;
        $this->Messages = array();
    }

    function findMessage( $context, $source, $comment = null )
    {
        $man = eZTranslatorManager::instance();
        $translation = $this->shuffleText( $source );
        return $man->createMessage( $context, $source, $comment, $translation );
    }

    /*!
     Reorders some of the characters in $text and returns the new text string.
     The maximum number of reorders is taken from MaxChars.
    */
    function &shuffleText( $text )
    {
        $num = mt_rand( 0, $this->MaxChars );
        for ( $i = 0; $i < $num; ++$i )
        {
            $len = strlen( $text );
            $offs = mt_rand( 0, $len - 1 );
            if ( $offs == 0 )
            {
                $tmp = $text[$offs];
                $text[$offs] = $text[$len - 1];
                $text[$len] = $tmp;
            }
            else
            {
                $delta = -1;
                if ( $text[$offs+$delta] == " " and
                     $offs + 1 < $len )
                    $delta = 1;
                $tmp = $text[$offs];
                $text[$offs] = $text[$offs+$delta];
                $text[$offs+$delta] = $tmp;
            }
        }
        return $text;
    }

    function translate( $context, $source, $comment = null )
    {
        $msg = $this->findMessage( $context, $source, $comment );
        if ( $msg !== null )
        {
            return $msg["translation"];
        }

        return null;
    }

    /// \privatesection
    /// Contains the hash table with cached 1337 translations
    public $Messages;
}

?>
