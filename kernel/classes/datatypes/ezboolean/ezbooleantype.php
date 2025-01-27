<?php
//
// Definition of eZBooleanType class
//
// Created on: <27-Jun-2002 18:24:54 sp>
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
  \class eZBooleanType ezbooleantype.php
  \ingroup eZDatatype
  \brief Stores a boolean value

*/

class eZBooleanType extends eZDataType
{
    const DATA_TYPE_STRING = "ezboolean";

    function eZBooleanType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "Checkbox", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_int' => 'value' ) ) );
    }

    /*!
     Store content
    */
    function storeObjectAttribute( $attribute )
    {
    }


   /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataInt = $originalContentObjectAttribute->attribute( "data_int" );
            $contentObjectAttribute->setAttribute( "data_int", $dataInt );
        }
        else
        {
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            $default = $contentClassAttribute->attribute( "data_int3" );
            $contentObjectAttribute->setAttribute( "data_int", $default );
        }
    }

    /*!
      Validates the http post var boolean input.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        if ( $contentObjectAttribute->validateIsRequired() and
             !$classAttribute->attribute( 'is_information_collector' ) )
        {
            if ( $http->hasPostVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) ) )
            {
                $data = $http->postVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) );
                if ( isset( $data ) )
                    return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                     'Input required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $contentObjectAttribute->validateIsRequired() )
        {
            if ( $http->hasPostVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) ) )
            {
                $data = $http->postVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) );
                if ( isset( $data ) )
                    return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                     'Input required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
        }
    }

    /*!
     Fetches the http post var boolean input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) ))
        {
            $data = $http->postVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) );
            if ( isset( $data ) && $data !== '0' && $data !== 'false' )
                $data = 1;
            else
                $data = 0;
        }
        else
        {
            $data = 0;
        }
        $contentObjectAttribute->setAttribute( "data_int", $data );
        return true;
    }

   /*!
    Fetches the http post variables for collected information
   */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) ))
        {
            $data = $http->postVariable( $base . "_data_boolean_" . $contentObjectAttribute->attribute( "id" ) );
            if ( isset( $data ) && $data !== '0' && $data !== 'false' )
                $data = 1;
            else
                $data = 0;
        }
        else
        {
            $data = 0;
        }
        $collectionAttribute->setAttribute( 'data_int', $data );
        return true;
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezboolean_default_value_' . $classAttribute->attribute( 'id' ) . '_exists' ) )
        {
            if ( $http->hasPostVariable( $base . "_ezboolean_default_value_" . $classAttribute->attribute( "id" ) ))
            {
                $data = $http->postVariable( $base . "_ezboolean_default_value_" . $classAttribute->attribute( "id" ) );
                if ( isset( $data ) )
                    $data = 1;
                $classAttribute->setAttribute( "data_int3", $data );
            }
            else
            {
                $classAttribute->setAttribute( "data_int3", 0 );
            }
        }
        return true;
    }

    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }
    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_int', $string );
    }

    function isIndexable()
    {
        return true;
    }

    function isInformationCollector()
    {
        return true;
    }

    function sortKey( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    function sortKeyType()
    {
        return 'int';
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }

    /*!
     Returns the integer value.
    */
    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return true;
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( 'data_int3' );
        $dom = $attributeParametersNode->ownerDocument;
        $defaultValueNode = $dom->createElement( 'default-value' );
        $defaultValueNode->setAttribute( 'is-set', $defaultValue ? 'true' : 'false' );
        $attributeParametersNode->appendChild( $defaultValueNode );
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {

        $defaultValue = strtolower( $attributeParametersNode->getElementsByTagName( 'default-value' )->item( 0 )->getAttribute( 'is-set' ) ) == 'true';
        $classAttribute->setAttribute( 'data_int3', $defaultValue );
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    function batchInitializeObjectAttributeData( $classAttribute )
    {
        $default = $classAttribute->attribute( "data_int3" );
        return array( 'data_int'     => $default,
                      'sort_key_int' => $default );
    }
}

eZDataType::register( eZBooleanType::DATA_TYPE_STRING, "eZBooleanType" );

?>
