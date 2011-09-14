<?php
//
// Definition of eZCollaborationEventType class
//
// Created on: <12-May-2003 13:29:25 sp>
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
  \class eZCollaborationEventType ezcollaborationeventtype.php
  \brief The class eZCollaborationEventType does

*/

class eZCollaborationEventType extends eZNotificationEventType
{
    const NOTIFICATION_TYPE_STRING = 'ezcollaboration';

    /*!
     Constructor
    */
    function eZCollaborationEventType()
    {
        $this->eZNotificationEventType( self::NOTIFICATION_TYPE_STRING );
    }

    function initializeEvent( $event, $params )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $params, 'params for type collaboration' );
        $event->setAttribute( 'data_int1', $params['collaboration_id'] );
        $event->setAttribute( 'data_text1', $params['collaboration_identifier'] );
    }

    function attributes()
    {
        return array_merge( array( 'collaboration_identifier',
                                   'collaboration_id' ),
                            eZNotificationEventType::attributes() );
    }

    function hasAttribute( $attributeName )
    {
        return in_array( $attributeName, $this->attributes() );
    }

    function attribute( $attributeName )
    {
        if ( $attributeName == 'collaboration_identifier' )
        {
            return eZNotificationEventType::attribute( 'data_text1' );
        }
        else if ( $attributeName == 'collaboration_id' )
        {
            return eZNotificationEventType::attribute( 'data_int1' );
        }

        return eZNotificationEventType::attribute( $attributeName );
    }

    function eventContent( $event )
    {
        return eZCollaborationItem::fetch( $event->attribute( 'data_int1' ) );
    }
}

eZNotificationEventType::register( eZCollaborationEventType::NOTIFICATION_TYPE_STRING, 'eZCollaborationEventType' );

?>
