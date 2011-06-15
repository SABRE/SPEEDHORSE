<?php
//
// Definition of ezbotrvideoType class
//
// SOFTWARE NAME: eZ Star Rating
// SOFTWARE RELEASE: 1.0-0
// COPYRIGHT NOTICE: Copyright (C) 2008 Bruce Morrison, 2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

class ezbotrvideoType extends eZDataType
{
	const DATA_TYPE_STRING = 'ezbotrvideo';

    /*!
     Construction of the class, note that the second parameter in eZDataType 
     is the actual name showed in the datatype dropdown list.
    */
    function __construct()
    {
        parent::__construct( self::DATA_TYPE_STRING, 'BOTR Video' );
    }

    /*!
      Validates the input and returns true if the input was
      valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $objectAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }


    /*!
    */
	function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
	{
	    if ( $http->hasPostVariable( $base . '_ezstring_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
	    {
	        $data = $http->postVariable( $base . '_ezstring_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
	        $contentObjectAttribute->setAttribute( 'data_text', $data );
	        return true;
	    }
	    return false;
	}

	/*!
	 Fetches the http post variables for collected information
*/
	function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
	{
	    if ( $http->hasPostVariable( $base . "_ezstring_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
	    {
	        $dataText = $http->postVariable( $base . "_ezstring_data_text_" . $contentObjectAttribute->attribute( "id" ) );
	        $collectionAttribute->setAttribute( 'data_text', $dataText );
	        return true;
	    }
	    return false;
	}

    /*!
     Store the content. Since the content has been stored in function 
     fetchObjectAttributeHTTPInput(), this function is with empty code.
    */
    function storeObjectAttribute( $objectattribute )
    {
    }

    /*!
     Returns the meta data used for storing search indices.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     Returns the text.
    */
    function title( $objectAttribute, $name = null)
    {
        return $this->metaData( $objectAttribute );
    }

    function isIndexable()
    {
        return true;
    }

    function sortKey( $objectAttribute )
    {
        return $this->metaData( $objectAttribute );
    }
  
    function sortKeyType()
    {
        return 'string';
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( 'data_text' ) ) != '';
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
		return $contentObjectAttribute->attribute( 'data_text' );
    }

    function objectDisplayInformation( $objectAttribute, $mergeInfo = false )
    {
        $info = array( 'edit' => array( 'grouped_input' => true ),
                       'collection' => array( 'grouped_input' => true ) );
        return eZDataType::objectDisplayInformation( $objectAttribute, $info );
    }


}

eZDataType::register( ezbotrvideoType::DATA_TYPE_STRING, 'ezbotrvideoType' );
