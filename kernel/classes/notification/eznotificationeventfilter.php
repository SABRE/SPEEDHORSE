<?php
//
// Definition of eZNotificationEventFilter class
//
// Created on: <09-May-2003 16:05:40 sp>
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
  \class eZNotificationEventFilter eznotificationeventfilter.php
  \brief The class eZNotificationEventFilter does

*/
class eZNotificationEventFilter
{
    /*!
     Constructor
    */
    function eZNotificationEventFilter()
    {
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function process()
    {
        $limit = 100;
        $offset = 0;
        $availableHandlers = eZNotificationEventFilter::availableHandlers();
        do
        {
            $eventList = eZNotificationEvent::fetchUnhandledList( array( 'offset' => $offset, 'length' => $limit ) );
            foreach( $eventList as $event )
            {
                $db = eZDB::instance();
                $db->begin();

                foreach( $availableHandlers as $handler )
                {
                    if ( $handler === false )
                    {
                        eZDebug::writeError( "Notification handler does not exist: $handlerKey", __METHOD__ );
                    }
                    else
                    {
                        $handler->handle( $event );
                    }
                }
                $itemCountLeft = eZNotificationCollectionItem::fetchCountForEvent( $event->attribute( 'id' ) );
                if ( $itemCountLeft == 0 )
                {
                    $event->remove();
                }
                else
                {
                    $event->setAttribute( 'status', eZNotificationEvent::STATUS_HANDLED );
                    $event->store();
                }

                $db->commit();
            }
            eZContentObject::clearCache();
        } while ( count( $eventList ) == $limit ); // If less than limit, we're on the last iteration

        eZNotificationCollection::removeEmpty();
    }

    static function availableHandlers()
    {
        $baseDirectory = eZExtension::baseDirectory();
        $notificationINI = eZINI::instance( 'notification.ini' );
        $availableHandlers = $notificationINI->variable( 'NotificationEventHandlerSettings', 'AvailableNotificationEventTypes' );
        $repositoryDirectories = array();
        $extensionDirectories = $notificationINI->variable( 'NotificationEventHandlerSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = $baseDirectory . '/' . $extensionDirectory . '/notification/handler';
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }
        $handlers = array();
        foreach( $availableHandlers as $handlerString )
        {
            $eventHandler = eZNotificationEventFilter::loadHandler( $repositoryDirectories, $handlerString );
            if ( is_object( $eventHandler ) )
                $handlers[$handlerString] = $eventHandler;
        }
        return $handlers;
    }

    static function loadHandler( $directories, $handlerString )
    {
        $foundHandler = false;
        $includeFile = '';

        $baseDirectory = eZExtension::baseDirectory();
        $notificationINI = eZINI::instance( 'notification.ini' );
        $repositoryDirectories = $notificationINI->variable( 'NotificationEventHandlerSettings', 'RepositoryDirectories' );
        $extensionDirectories = $notificationINI->variable( 'NotificationEventHandlerSettings', 'ExtensionDirectories' );
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            $extensionPath = "{$baseDirectory}/{$extensionDirectory}/notification/handler/";
            if ( file_exists( $extensionPath ) )
                $repositoryDirectories[] = $extensionPath;
        }

        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            $repositoryDirectory = trim( $repositoryDirectory, '/' );
            $includeFile = "{$repositoryDirectory}/{$handlerString}/{$handlerString}handler.php";
            if ( file_exists( $includeFile ) )
            {
                $foundHandler = true;
                break;
            }
        }
        if ( !$foundHandler  )
        {
            eZDebug::writeError( "Notification handler does not exist: $handlerString", __METHOD__ );
            return false;
        }
        include_once( $includeFile );
        $className = $handlerString . "handler";
        return new $className();
    }

    /*!
     \static
     Goes through all event handlers and tells them to cleanup.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $availableHandlers = eZNotificationEventFilter::availableHandlers();

        $db = eZDB::instance();
        $db->begin();
        foreach( $availableHandlers as $handler )
        {
            if ( $handler !== false )
            {
                $handler->cleanup();
            }
        }
        $db->commit();
    }
}

?>
