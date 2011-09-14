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
  \class eZEnumObjectValue ezenumobjectvalue.php
  \brief The class eZEnumObjectValue stores chosen enum values of an object attribute

*/

class eZEnumObjectValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZEnumObjectValue( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "contentobject_attribute_id" => array( 'name' => "ContentObjectAttributeID",
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true,
                                                                                'foreign_class' => 'eZContentObjectAttribute',
                                                                                'foreign_attribute' => 'id',
                                                                                'multiplicity' => '1..*' ),
                                         "contentobject_attribute_version" => array( 'name' => "ContentObjectAttributeVersion",
                                                                                     'datatype' => 'integer',
                                                                                     'default' => 0,
                                                                                     'required' => true,
                                                                                     'short_name' => 'contentobject_attr_version' ),
                                         "enumid" => array( 'name' => "EnumID",
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         "enumelement" => array( 'name' => "EnumElement",
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "enumvalue" => array( 'name' => "EnumValue",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ) ),
                      "keys" => array( "contentobject_attribute_id", "contentobject_attribute_version", "enumid" ),
                      "sort" => array( "contentobject_attribute_id" => "asc" ),
                      "class_name" => "eZEnumObjectValue",
                      "name" => "ezenumobjectvalue" );
    }

    static function create( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumID, $enumElement, $enumValue )
    {
        $row = array( "contentobject_attribute_id" => $contentObjectAttributeID,
                      "contentobject_attribute_version" => $contentObjectAttributeVersion,
                      "enumid" => $enumID,
                      "enumelement" =>  $enumElement,
                      "enumvalue" => $enumValue );
        return new eZEnumObjectValue( $row );
    }

    static function removeAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion )
    {
        if( $contentObjectAttributeVersion == null )
        {
            eZPersistentObject::removeObject( eZEnumObjectValue::definition(),
                                              array( "contentobject_attribute_id" => $contentObjectAttributeID ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZEnumObjectValue::definition(),
                                              array( "contentobject_attribute_id" => $contentObjectAttributeID,
                                                     "contentobject_attribute_version" => $contentObjectAttributeVersion ) );
        }
    }

    function removeByOAID( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumid )
    {
        eZPersistentObject::removeObject( eZEnumObjectValue::definition(),
                                          array( "enumid" => $enumid,
                                                 "contentobject_attribute_id" => $contentObjectAttributeID,
                                                 "contentobject_attribute_version" => $contentObjectAttributeVersion ) );
    }

    static function fetch( $contentObjectAttributeID, $contentObjectAttributeVersion, $enumid, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZEnumObjectValue::definition(),
                                                null,
                                                array(  "contentobject_attribute_id" => $contentObjectAttributeID,
                                                        "contentobject_attribute_version" => $contentObjectAttributeVersion,
                                                        "enumid" => $enumid ),
                                                $asObject );
    }

    static function fetchAllElements( $contentObjectAttributeID, $contentObjectAttributeVersion, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZEnumObjectValue::definition(),
                                                    null,
                                                    array( "contentobject_attribute_id" => $contentObjectAttributeID,
                                                           "contentobject_attribute_version" => $contentObjectAttributeVersion ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    public $ContentObjectAttributeID;
    public $ContentObjectAttributeVersion;
    public $EnumID;
    public $EnumElement;
    public $EnumValue;
}

?>
