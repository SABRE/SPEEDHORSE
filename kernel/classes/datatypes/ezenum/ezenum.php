<?php
//
// Definition of eZEnum class
//
// Created on: <24-��-2002 16:07:05 wy>
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
  \class eZEnum ezenum.php
  \ingroup eZDatatype
  \brief The class eZEnum does

*/

class eZEnum
{
    /*!
     Constructor
    */
    function eZEnum( $id, $version )
    {
        $this->ClassAttributeID = $id;
        $this->ClassAttributeVersion = $version;
        $this->Enumerations = eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );
        $this->IsmultipleEnum = null;
        $this->IsoptionEnum = null;
        $this->ObjectEnumerations = null;
    }

    function attributes()
    {
        return array( 'contentclass_attributeid',
                      'contentclass_attributeversion',
                      'enum_list',
                      'enumobject_list',
                      'enum_ismultiple',
                      'enum_isoption' );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        switch ( $attr )
        {
            case "contentclass_attributeid" :
            {
                return $this->ClassAttributeID;
            }break;
            case "contentclass_attributeversion" :
            {
                return $this->ClassAttributeVersion;
            }break;
            case "enum_list" :
            {
                return $this->Enumerations;
            }break;
            case "enumobject_list" :
            {
                return $this->ObjectEnumerations;
            }break;
            case "enum_ismultiple" :
            {
                return $this->IsmultipleEnum;
            }break;
            case "enum_isoption" :
            {
                return $this->IsoptionEnum;
            }break;
            default :
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
                return null;
            }break;
        }
    }

    function setObjectEnumValue( $contentObjectAttributeID, $contentObjectAttributeVersion ){
        $this->ObjectEnumerations = eZEnumObjectValue::fetchAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion );
    }

    static function removeObjectEnumerations( $contentObjectAttributeID, $contentObjectAttributeVersion )
    {
         eZEnumObjectValue::removeAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion );
    }

    static function storeObjectEnumeration( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumID, $enumElement, $enumValue )
    {
        $enumobjectvalue = eZEnumObjectValue::create( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumID, $enumElement, $enumValue );
        $enumobjectvalue->store();
    }

    function setIsmultipleValue( $value )
    {
        $this->IsmultipleEnum = $value;
    }

    function setIsoptionValue( $value )
    {
        $this->IsoptionEnum = $value;
    }

    function setValue( $array_enumid, $array_enumelement, $array_enumvalue, $version )
    {
        $db = eZDB::instance();
        $db->begin();

        for ($i=0;$i<count( $array_enumid );$i++ )
        {
            $enumvalue = eZEnumValue::fetch( $array_enumid[$i], $version );
            $enumvalue->setAttribute( "enumelement", $array_enumelement[$i] );
            $enumvalue->setAttribute( "enumvalue", $array_enumvalue[$i] );
            $enumvalue->store();
            $this->Enumerations = eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );
        }
        $db->commit();
    }

    function setVersion( $version )
    {
        if ( $version == $this->ClassAttributeVersion )
            return;

        $db = eZDB::instance();
        $db->begin();

        eZEnumValue::removeAllElements( $this->ClassAttributeID, 0 );
        foreach( $this->Enumerations as $enum )
        {
            $oldversion = $enum->attribute ( "contentclass_attribute_version" );
            $id = $enum->attribute( "id" );
            $contentClassAttributeID = $enum->attribute( "contentclass_attribute_id" );
            $element = $enum->attribute( "enumelement" );
            $value = $enum->attribute( "enumvalue" );
            $placement = $enum->attribute( "placement" );
            $enumCopy = eZEnumValue::createCopy( $id,
                                                 $contentClassAttributeID,
                                                 0,
                                                 $element,
                                                 $value,
                                                 $placement );
            $enumCopy->store();
            if ( $oldversion != $version )
            {
                $enum->setAttribute("contentclass_attribute_version", $version );
                $enum->store();
            }
        }

        $this->Enumerations = eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );

        $db->commit();
    }

    static function removeOldVersion( $id, $version )
    {
        eZEnumValue::removeAllElements( $id, $version );
    }

    /*!
     Adds an enumeration
    */
    function addEnumeration( $element )
    {
        $enumvalue = eZEnumValue::create( $this->ClassAttributeID, $this->ClassAttributeVersion, $element );
        $enumvalue->store();
        $this->Enumerations = eZEnumValue::fetchAllElements( $this->ClassAttributeID, $this->ClassAttributeVersion );
    }

    /*!
     Adds the enumeration value object \a $enumValue to the enumeration list.
    */
    function addEnumerationValue( $enumValue )
    {
        $this->Enumerations[] = $enumValue;
    }

    function removeEnumeration( $id, $enumid, $version )
    {
       eZEnumValue::removeByID( $enumid, $version );
       $this->Enumerations = eZEnumValue::fetchAllElements( $id, $version );
    }

    public $Enumerations;
    public $ObjectEnumerations;
    public $ClassAttributeID;
    public $ClassAttributeVersion;
    public $IsmultipleEnum;
    public $IsoptionEnum;
}

?>
