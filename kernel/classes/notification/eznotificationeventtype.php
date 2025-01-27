<?php
//
// Definition of eZNotificationEventType class
//
// Created on: <12-May-2003 09:58:12 sp>
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
  \class eZNotificationEventType eznotificationeventtype.php
  \brief The class eZNotificationEventType does

*/

class eZNotificationEventType
{
    /*!
     Constructor
    */
    function eZNotificationEventType( $notificationEventTypeString )
    {
        $this->NotificationEventTypeString = $notificationEventTypeString;
    }

    function initializeEvent( $event, $params )
    {
    }

    /*!
     \static
     Crates a datatype instance of the datatype string id \a $dataTypeString.
     \note It only creates one instance for each datatype.
    */
    static function create( $notificationEventTypeString )
    {
        $types =& $GLOBALS["eZNotificationEventTypes"];
        if( !isset( $types[$notificationEventTypeString] ) )
        {
            eZDebugSetting::writeDebug( 'kernel-notification', $types, 'notification types' );
            eZNotificationEventType::loadAndRegisterType( $notificationEventTypeString );
            eZDebugSetting::writeDebug( 'kernel-notification', $types, 'notification types 2' );
        }
        $def = null;
        if ( isset( $types[$notificationEventTypeString] ) )
        {
            $className = $types[$notificationEventTypeString];
            $def =& $GLOBALS["eZNotificationEventTypeObjects"][$notificationEventTypeString];

            if ( !is_object( $def ) || strtolower( get_class( $def ) ) != $className )
            {
                $def = new $className();
            }
        }
        return $def;
    }


    function attributes()
    {
        return array_merge( array( 'description' ),
                            array_keys( $this->Attributes ) );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        if ( $attr == "description" )
        {
            return  $this->eventDescription();
        }
        if ( isset( $this->Attributes[$attr] ) )
        {
            return $this->Attributes[$attr];
        }

        eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
        return null;
    }

    function eventDescription()
    {
        return $this->Attributes["name"];
    }

    function execute( $event )
    {
    }

    function eventContent( $event )
    {
        return "";
    }

    static function allowedTypes()
    {
        $allowedTypes = $GLOBALS["eZNotificationEventTypeAllowedTypes"];
        if ( !is_array( $allowedTypes ) )
        {
            $notificationINI = eZINI::instance( 'notification.ini' );
            $eventTypes = $notificationINI->variable( 'NotificationEventTypeSettings', 'AvailableEventTypes' );
            $allowedTypes = array_unique( $eventTypes );
        }
        return $allowedTypes;
    }

    static function loadAndRegisterAllTypes()
    {
        $allowedTypes = eZNotificationEventType::allowedTypes();
        foreach( $allowedTypes as $type )
        {
            eZNotificationEventType::loadAndRegisterType( $type );
        }
    }

    static function loadAndRegisterType( $type )
    {
        $types = $GLOBALS["eZNotificationEventTypes"];
        if ( isset( $types[$type] ) )
        {
            eZDebug::writeError( "Notification event type already registered: $type", __METHOD__ );
            return false;
        }

        $baseDirectory = eZExtension::baseDirectory();
        $notificationINI = eZINI::instance( 'notification.ini' );
        $repositoryDirectories = $notificationINI->variable( 'NotificationEventTypeSettings', 'RepositoryDirectories' );
        $extensionDirectories = $notificationINI->variable( 'NotificationEventTypeSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/notificationtypes';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }
        $foundEventType = false;
        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $includeFile = "$repositoryDirectory/$type/" . $type . "type.php";
            if ( file_exists( $includeFile ) )
            {
                $foundEventType = true;
                break;
            }
        }
        if ( !$foundEventType )
        {
            eZDebug::writeError( "Notification event type not found: $type, searched in these directories: " . implode( ', ', $repositoryDirectories ), __METHOD__ );
            return false;
        }
        include_once( $includeFile );
        return true;
    }

    static function register( $notificationTypeString, $className )
    {
        if ( !isset( $GLOBALS["eZNotificationEventTypes"] ) || !is_array( $GLOBALS["eZNotificationEventTypes"] ) )
        {
            $types = array();
        }
        $GLOBALS["eZNotificationEventTypes"][$notificationTypeString] = $className;
    }




}

?>
