<?php
//
// Definition of eZSearchFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
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
  \class eZSearchFunctionCollection ezsearchfunctioncollection.php
  \brief The class eZSearchFunctionCollection does

*/

class eZSearchFunctionCollection
{
    /*!
     Constructor
    */
    function eZSearchFunctionCollection()
    {
    }

    function fetchSearchListCount()
    {
        $db = eZDB::instance();
        $query = "SELECT count(*) as count FROM ezsearch_search_phrase";
        $searchListCount = $db->arrayQuery( $query );

        return array( 'result' => $searchListCount[0]['count'] );
    }

    function fetchSearchList( $offset, $limit )
    {
        $parameters = array( 'offset' => $offset, 'limit'  => $limit );
        $mostFrequentPhraseArray = eZSearchLog::mostFrequentPhraseArray( $parameters );

        return array( 'result' => $mostFrequentPhraseArray );
    }

}

?>
