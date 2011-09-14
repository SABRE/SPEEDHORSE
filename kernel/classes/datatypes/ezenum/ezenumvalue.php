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
  \class eZEnumValue ezenumvalue.php
  \ingroup eZDatatype
  \brief The class eZEnumValue does

*/

class eZEnumValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZEnumValue( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "contentclass_attribute_id" => array( 'name' => "ContentClassAttributeID",
                                                                               'datatype' => 'integer',
                                                                               'default' => 0,
                                                                               'required' => true,
                                                                               'foreign_class' => 'eZContentClassAttribute',
                                                                               'foreign_attribute' => 'id',
                                                                               'multiplicity' => '1..*' ),
                                         "contentclass_attribute_version" => array( 'name' => "ContentClassAttributeVersion",
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
                                                               'required' => true ),
                                         "placement" => array( 'name' => "Placement",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      "keys" => array( "id", "contentclass_attribute_id", "contentclass_attribute_version" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZEnumValue",
                      "name" => "ezenumvalue" );
    }

    function __clone()
    {
        unset( $this->ID );
    }

    static function create( $contentClassAttributeID, $contentClassAttributeVersion, $element )
    {
        $row = array( "id" => null,
                      "contentclass_attribute_id" => $contentClassAttributeID,
                      "contentclass_attribute_version" => $contentClassAttributeVersion,
                      "enumvalue" => "",
                      "enumelement" => $element,
                      "placement" => eZPersistentObject::newObjectOrder( eZEnumValue::definition(),
                                                                         "placement",
                                                                         array( "contentclass_attribute_id" => $contentClassAttributeID,
                                                                                "contentclass_attribute_version" => $contentClassAttributeVersion ) ) );
        return new eZEnumValue( $row );
    }

    static function createCopy( $id, $contentClassAttributeID, $contentClassAttributeVersion, $element, $value, $placement )
    {
        $row = array( "id" => $id,
                      "contentclass_attribute_id" => $contentClassAttributeID,
                      "contentclass_attribute_version" => $contentClassAttributeVersion,
                      "enumvalue" => $value,
                      "enumelement" => $element,
                      "placement" => $placement );
        return new eZEnumValue( $row );
    }

    static function removeAllElements( $contentClassAttributeID, $version )
    {
        eZPersistentObject::removeObject( eZEnumValue::definition(),
                                          array( "contentclass_attribute_id" => $contentClassAttributeID,
                                                 "contentclass_attribute_version" => $version) );
    }

    static function removeByID( $id , $version )
    {
        eZPersistentObject::removeObject( eZEnumValue::definition(),
                                          array( "id" => $id,
                                                 "contentclass_attribute_version" => $version) );
    }

    static function fetch( $id, $version, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZEnumValue::definition(),
                                                null,
                                                array( "id" => $id,
                                                       "contentclass_attribute_version" => $version),
                                                $asObject );
    }

    static function fetchAllElements( $classAttributeID, $version, $asObject = true )
    {
        if ( $classAttributeID === null )
        {
            return array();
        }

        return eZPersistentObject::fetchObjectList( eZEnumValue::definition(),
                                                    null,
                                                    array( "contentclass_attribute_id" => $classAttributeID,
                                                           "contentclass_attribute_version" => $version ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    public $ID;
    public $ContentClassAttributeID;
    public $ContentClassAttributeVersion;
    public $EnumElement;
    public $EnumValue;
    public $Placement;
}

?>
