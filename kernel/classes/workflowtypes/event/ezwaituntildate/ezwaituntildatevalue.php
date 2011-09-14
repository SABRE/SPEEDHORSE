<?php
//
// Definition of eZWaitUntilDateValue class
//
// Created on: <14-���-2003 14:49:06 sp>
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
  \class eZWaitUntilDateValue ezwaituntildatevalue.php
  \brief The class eZWaitUntilDateValue does

*/

class eZWaitUntilDateValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZWaitUntilDateValue( $row )
    {
        $this->eZPersistentObject( $row );
        $this->ClassName = null;
        $this->ClassAttributeName = null;

    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "workflow_event_id" => array( 'name' => "WorkflowEventID",
                                                                       'datatype' => 'integer',
                                                                       'default' => 0,
                                                                       'required' => true,
                                                                       'foreign_class' => 'eZWorkflowEvent',
                                                                       'foreign_attribute' => 'id',
                                                                       'multiplicity' => '1..*' ),
                                         "workflow_event_version" => array( 'name' => "WorkflowEventVersion",
                                                                            'datatype' => 'integer',
                                                                            'default' => 0,
                                                                            'required' => true ),
                                         "contentclass_id" => array( 'name' => "ContentClassID",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZContentClass',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
                                         "contentclass_attribute_id" => array( 'name' => "ContentClassAttributeID",
                                                                               'datatype' => 'integer',
                                                                               'default' => 0,
                                                                               'required' => true,
                                                                               'foreign_class' => 'eZContentClassAttribute',
                                                                               'foreign_attribute' => 'id',
                                                                               'multiplicity' => '1..*' ) ),
                      "keys" => array( "id", "workflow_event_id", "workflow_event_version" ),
                      "function_attributes" => array( "class_name" => "className",
                                                      "classattribute_name" => "classAttributeName" ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZWaitUntilDateValue",
                      "name" => "ezwaituntildatevalue" );
    }

    function className()
    {
        if ( $this->ClassName === null )
        {
            $contentClass = eZContentClass::fetch( $this->attribute( 'contentclass_id' ) );
            $this->ClassName = $contentClass->attribute( 'name' );
        }
        return $this->ClassName;
    }

    function classAttributeName()
    {
        if ( $this->ClassAttributeName === null )
        {
            $contentClassAttribute = eZContentClassAttribute::fetch( $this->attribute( 'contentclass_attribute_id' ) );
            $this->ClassAttributeName = $contentClassAttribute->attribute( 'name' );
        }
        return $this->ClassAttributeName;
    }

    function __clone()
    {
        unset( $this->ClassName );
        unset( $this->ClassAttributeName );
    }

    static function create( $workflowEventID, $workflowEventVersion, $contentClassAttributeID, $contentClassID )
    {
        $row = array( "id" => null,
                      "workflow_event_id" => $workflowEventID,
                      "workflow_event_version" => $workflowEventVersion,
                      "contentclass_id" => $contentClassID,
                      "contentclass_attribute_id" => $contentClassAttributeID
                      );
        return new eZWaitUntilDateValue( $row );
    }

    static function createCopy( $id, $workflowEventID, $workflowEventVersion,  $contentClassID , $contentClassAttributeID )
    {
        $row = array( "id" => $id,
                      "workflow_event_id" => $workflowEventID,
                      "workflow_event_version" => $workflowEventVersion,
                      "contentclass_id" => $contentClassID,
                      "contentclass_attribute_id" => $contentClassAttributeID );
        return new eZWaitUntilDateValue( $row );
    }


    static function removeAllElements( $workflowEventID, $version )
    {
        eZPersistentObject::removeObject( eZWaitUntilDateValue::definition(),
                                          array( "workflow_event_id" => $workflowEventID,
                                                 "workflow_event_version" => $version) );
    }

    static function removeByID( $id , $version )
    {
        eZPersistentObject::removeObject( eZWaitUntilDateValue::definition(),
                                          array( "id" => $id,
                                                 "workflow_event_version" => $version) );
    }

    function fetch( $id, $version, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZWaitUntilDateValue::definition(),
                                                null,
                                                array( "id" => $id,
                                                       "workflow_event_version" => $version),
                                                $asObject );
    }

    static function fetchAllElements( $workflowEventID, $version, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWaitUntilDateValue::definition(),
                                                    null,
                                                    array( "workflow_event_id" => $workflowEventID,
                                                           "workflow_event_version" => $version ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    public $ClassName;
    public $ClassAttributeName;
}

?>
