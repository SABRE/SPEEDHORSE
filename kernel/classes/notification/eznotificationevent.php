<?php
//
// Definition of eZNotificationEvent class
//
// Created on: <09-May-2003 16:03:28 sp>
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
  \class eZNotificationEvent eznotificationevent.php
  \brief The class eZNotificationEvent does

*/
class eZNotificationEvent extends eZPersistentObject
{
    const STATUS_CREATED = 0;
    const STATUS_HANDLED = 1;

    /*!
     Constructor
    */
    function eZNotificationEvent( $row = array() )
    {
        $this->eZPersistentObject( $row );
        $this->TypeString = $this->attribute( 'event_type_string' );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "status" => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         "event_type_string" => array( 'name' => "EventTypeString",
                                                                       'datatype' => 'string',
                                                                       'default' => '',
                                                                       'required' => true ),
                                         "data_int1" => array( 'name' => "DataInt1",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_int2" => array( 'name' => "DataInt2",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_int3" => array( 'name' => "DataInt3",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_int4" => array( 'name' => "DataInt4",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "data_text1" => array( 'name' => "DataText1",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "data_text2" => array( 'name' => "DataText2",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "data_text3" => array( 'name' => "DataText3",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         "data_text4" => array( 'name' => "DataText4",
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'content' => 'content' ),
                      "increment_key" => "id",
                      "sort" => array( "id" => "asc" ),
                      "class_name" => "eZNotificationEvent",
                      "name" => "eznotificationevent" );
    }

    static function create( $type, $params = array() )
    {
        $row = array(
            "id" => null,
            'event_type_string' => $type,
            'data_int1' => 0,
            'data_int2' => 0,
            'data_int3' => 0,
            'data_int4' => 0,
            'data_text1' => '',
            'data_text2' => '',
            'data_text3' => '',
            'data_text4' => '' );
        $event = new eZNotificationEvent( $row );
        eZDebugSetting::writeDebug( 'kernel-notification', $event, "event" );
        $event->initializeEventType( $params );
        return $event;
    }

    function initializeEventType( $params = array() )
    {
        $eventType = $this->eventType();
        $eventType->initializeEvent( $this, $params );
        eZDebugSetting::writeDebug( 'kernel-notification', $this, 'event after initialization' );
    }

    function eventType()
    {
        if ( ! isset ( $this->EventType ) )
        {
            $this->EventType = eZNotificationEventType::create( $this->TypeString );
        }
        return $this->EventType;
    }


    /*!
     Returns the content for this event.
    */
    function content()
    {
        if ( $this->Content === null )
        {
            $eventType = $this->eventType();
            $this->Content = $eventType->eventContent( $this );
        }
        return $this->Content;
    }

    /*!
     Sets the content for the current event
    */
    function setContent( $content )
    {
        $this->Content = $content;
    }

    /**
     * Fetches notification events as objects, and returns them in an array.
     *
     * The optional $limit can be used to set an offset and a limit for the fetch. It is
     * passed to {@link eZPersistentObject::fetchObjectList()} and should be used in the same way.
     *
     * @static
     * @param array $limit An associative array with limitiations, can contain
     *                     - offset - Numerical value defining the start offset for the fetch
     *                     - length - Numerical value defining the max number of items to return
     * @return array An array of eZNotificationEvent objects
     */
    static function fetchList( $limit = null )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationEvent::definition(),
                                                    null,  null, null, $limit,
                                                    true );
    }

    static function fetch( $eventID )
    {
        return eZPersistentObject::fetchObject( eZNotificationEvent::definition(),
                                                null,
                                                array( 'id' => $eventID ) );
    }

    /**
     * Fetches unhandled notification events as objects, and returns them in an array.
     *
     * The optional $limit can be used to set an offset and a limit for the fetch. It is
     * passed to {@link eZPersistentObject::fetchObjectList()} and should be used in the same way.
     *
     * @static
     * @param array $limit An associative array with limitiations, can contain
     *                     - offset - Numerical value defining the start offset for the fetch
     *                     - length - Numerical value defining the max number of items to return
     * @return array An array of eZNotificationEvent objects
     */
    static function fetchUnhandledList( $limit = null )
    {
        return eZPersistentObject::fetchObjectList( eZNotificationEvent::definition(),
                                                    null, array( 'status' => self::STATUS_CREATED ), null, $limit,
                                                    true );
    }

    /*!
     \static
     Removes all notification events.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM eznotificationevent" );
    }

    public $Content = null;
}

?>
