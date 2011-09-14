<?php
//
// Definition of eZWaitUntilDate class
//
// Created on: <09-Jan-2003 16:20:05 sp>
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
  \class eZWaitUntilDate ezwaituntildate.php
  \brief The class eZWaitUntilDate does

*/
class eZWaitUntilDate
{
    function eZWaitUntilDate( $eventID, $eventVersion )
    {
        $this->WorkflowEventID = $eventID;
        $this->WorkflowEventVersion = $eventVersion;
        $this->Entries = eZWaitUntilDateValue::fetchAllElements( $eventID, $eventVersion );
    }

    function attributes()
    {
        return array( 'workflow_event_id',
                      'workflow_event_version',
                      'entry_list',
                      'classattribute_id_list' );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        switch ( $attr )
        {
            case "workflow_event_id" :
            {
                return $this->WorkflowEventID;
            }break;
            case "workflow_event_version" :
            {
                return $this->WorkflowEventVersion;
            }break;
            case "entry_list" :
            {
                return $this->Entries;
            }break;
            case 'classattribute_id_list' :
            {
                return $this->classAttributeIDList();
            }
            default :
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
                return null;
            }break;
        }
    }
    static function removeWaitUntilDateEntries( $workflowEventID, $workflowEventVersion )
    {
         eZWaitUntilDateValue::removeAllElements( $workflowEventID, $workflowEventVersion );
    }
    /*!
     Adds an enumeration
    */
    function addEntry( $contentClassAttributeID, $contentClassID = false )
    {
        if ( !isset( $contentClassAttributeID ) )
        {
            return;
        }
        if ( !$contentClassID )
        {
            $contentClassAttribute = eZContentClassAttribute::fetch( $contentClassAttributeID );
            $contentClassID = $contentClassAttribute->attribute( 'contentclass_id' );
        }
        // Checking if $contentClassAttributeID and $contentClassID already exist (in Entries)
        foreach ( $this->Entries as $entrie )
        {
            if ( $entrie->attribute( 'contentclass_attribute_id' ) == $contentClassAttributeID and
                 $entrie->attribute( 'contentclass_id' ) == $contentClassID )
                return;
        }
        $waitUntilDateValue = eZWaitUntilDateValue::create( $this->WorkflowEventID, $this->WorkflowEventVersion, $contentClassAttributeID, $contentClassID );
        $waitUntilDateValue->store();
        $this->Entries = eZWaitUntilDateValue::fetchAllElements( $this->WorkflowEventID, $this->WorkflowEventVersion );
    }

    function removeEntry( $workflowEventID, $id, $version )
    {
       eZWaitUntilDateValue::removeByID( $id, $version );
       $this->Entries = eZWaitUntilDateValue::fetchAllElements( $workflowEventID, $version );
    }

    function classAttributeIDList()
    {
        $attributeIDList = array();
        foreach ( $this->Entries as $entry )
        {
            $attributeIDList[] = $entry->attribute( 'contentclass_attribute_id' );
        }
        return $attributeIDList;
    }

    function setVersion( $version )
    {
        if ( $version == 1 && count( $this->Entries ) == 0 )
        {
            $this->Entries = eZWaitUntilDateValue::fetchAllElements( $this->WorkflowEventID, 0 );
            foreach( $this->Entries as $entry )
            {
                $entry->setAttribute( "workflow_event_version", 1 );
                $entry->store();
            }
        }
        if ( $version == 0 )
        {
            eZWaitUntilDateValue::removeAllElements( $this->WorkflowEventID, 0 );
            foreach( $this->Entries as $entry )
            {
                $oldversion = $entry->attribute( "workflow_event_version" );
                $id = $entry->attribute( "id" );
                $workflowEventID = $entry->attribute( "workflow_event_id" );
                $contentClassID = $entry->attribute( "contentclass_id" );
                $contentClassAttributeID = $entry->attribute( "contentclass_attribute_id" );
                $entryCopy = eZWaitUntilDateValue::createCopy( $id,
                                                               $workflowEventID,
                                                               0,
                                                               $contentClassID,
                                                               $contentClassAttributeID );

                $entryCopy->store();
            }
        }
    }


    public $WorkflowEventID;
    public $WorkflowEventVersion;
    public $Entries;

}


?>
