<?php
//
// Definition of eZRandomTranslator class
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
  \class eZRandomTranslator eztranslatorgroup.php
  \ingroup eZTranslation
  \brief Translates text by picking randomly among it's sub handlers

*/

class eZRandomTranslator extends eZTranslatorGroup
{
    /*!
     Constructor
    */
    function eZRandomTranslator( $is_key_based )
    {
        $this->eZTranslatorGroup( $is_key_based );
        mt_srand();
    }

    /*!
     Returns a random pick from the registered handlers.
    */
    function keyPick( $key )
    {
        if ( $this->handlerCount() == 0 )
            return -1;
        return mt_rand( 0, $this->handlerCount() - 1 );
    }

    /*!
     Returns a random pick from the registered handlers.
    */
    function pick( $context, $source, $comment )
    {
        if ( $this->handlerCount() == 0 )
            return -1;
        return mt_rand( 0, $this->handlerCount() - 1 );
    }

    /*!
     \private
     Generates a seed usable for srand() and returns it.
     DEPRECATED: as of eZ Publish 4.2 (seeding is not needed as of PHP 4.2.0)
    */
    function makeSeed()
    {
        $seed = microtime( true ) * 100000;
        return $seed;
    }
}

?>
