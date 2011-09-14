<?php
//
// Definition of eZInfocollectorFunctionCollection class
//
// Created on: <03-Oct-2006 13:05:24 sp>
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
  \class eZInfocollectorFunctionCollection ezinfocollectorfunctioncollection.php
  \brief The class eZInfocollectorFunctionCollection does

*/

class eZInfocollectorFunctionCollection
{
    /*!
     Constructor
    */
    function eZInfocollectorFunctionCollection()
    {
    }

    static public function fetchCollectedInfoCount( $objectAttributeID, $objectID, $value, $creatorID = false, $userIdentifier = false )
    {
        if ( $objectAttributeID )
            $count = eZInformationCollection::fetchCountForAttribute( $objectAttributeID, $value );
        else
            $count = eZInformationCollection::fetchCollectionsCount( $objectID, $creatorID, $userIdentifier );
        return array( 'result' => $count );
    }

    static public function fetchCollectedInfoCountList( $objectAttributeID )
    {
        $count = eZInformationCollection::fetchCountList( $objectAttributeID );
        return array( 'result' => $count );
    }

    static public function fetchCollectedInfoCollection( $collectionID, $contentObjectID )
    {
        $collection = false;
        if ( $collectionID )
            $collection = eZInformationCollection::fetch( $collectionID );
        else if ( $contentObjectID )
        {
            $userIdentifier = eZInformationCollection::currentUserIdentifier();
            $collection = eZInformationCollection::fetchByUserIdentifier( $userIdentifier, $contentObjectID );
        }
        return array( 'result' => $collection );
    }

    static public function fetchCollectionsList( $objectID = false, $creatorID = false, $userIdentifier = false, $limit = false, $offset = false, $sortBy = false )
    {
        $collection = eZInformationCollection::fetchCollectionsList( $objectID,
                                                                     $creatorID,
                                                                     $userIdentifier,
                                                                     array( 'limit' => $limit, 'offset' => $offset ),
                                                                     $sortBy
                                                                   );
        return array( 'result' => $collection );
     }


}

?>
