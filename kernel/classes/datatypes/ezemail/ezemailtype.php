<?php
//
// Definition of eZEmailType class
//
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
  \class eZEmailType ezemailtype.php
  \ingroup eZDatatype
  \brief Stores an email address

*/

class eZEmailType extends eZDataType
{
    const DATA_TYPE_STRING = "ezemail";

    function eZEmailType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "Email", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_text' => 'email' ) ) );
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    /*
     Private method, only for using inside this class.
    */
    function validateEMailHTTPInput( $email, $contentObjectAttribute )
    {
        if ( !eZMail::validate( $email ) )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                 'The email address is not valid.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        if ( $http->hasPostVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $email = $http->postVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) );

            $trimmedEmail = trim( $email );

            if ( $trimmedEmail == "" )
            {
                // we require user to enter an address only if the attribute is not an informationcollector
                if ( !$classAttribute->attribute( 'is_information_collector' ) and
                     $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                         'The email address is empty.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
            else
            {
                // if the entered address is not empty then we should validate it in any case
                return $this->validateEMailHTTPInput( $trimmedEmail, $contentObjectAttribute );
            }
        }
        else if ( !$classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Missing email input.' ) );
            return eZInputValidator::STATE_INVALID;
        }

        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) );
            $contentObjectAttribute->setAttribute( "data_text", $data );
            return true;
        }
        return false;
    }

    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $email = $http->postVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) );
            $classAttribute = $contentObjectAttribute->contentClassAttribute();

            $trimmedEmail = trim( $email );

            if ( $trimmedEmail == "" )
            {
                // if entered email is empty and required then return state invalid
                if ( $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                         'The email address is empty.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
                else
                    return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                // if entered email is not empty then we should validate it in any case
                return $this->validateEMailHTTPInput( $trimmedEmail, $contentObjectAttribute );
            }
        }
        else
            return eZInputValidator::STATE_INVALID;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $dataText = $http->postVariable( $base . "_data_text_" . $contentObjectAttribute->attribute( "id" ) );
            $collectionAttribute->setAttribute( 'data_text', $dataText );
            return true;
        }
        return false;
    }

    /*!
     Store the content.
    */
    function storeObjectAttribute( $attribute )
    {
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    function isIndexable()
    {
        return true;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_text', $string );
    }

    /*!
     Returns the text.
    */
    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( "data_text" ) ) != '';
    }

    function isInformationCollector()
    {
        return true;
    }

    function sortKey( $contentObjectAttribute )
    {
        return strtolower( $contentObjectAttribute->attribute( 'data_text' ) );
    }

    function sortKeyType()
    {
        return 'string';
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }
}

eZDataType::register( eZEmailType::DATA_TYPE_STRING, "eZEmailType" );

?>
