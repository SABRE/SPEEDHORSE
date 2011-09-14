<?php
//
// Definition of eZCurrentTimeType class
//
// Created on: <16-May-2003 10:11:48 sp>
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
  \class eZCurrentTimeType ezcurrenttimetype.php
  \brief The class eZCurrentTimeType does

*/
class eZCurrentTimeType extends eZNotificationEventType
{
    const NOTIFICATION_TYPE_STRING = 'ezcurrenttime';

    /*!
     Constructor
    */
    function eZCurrentTimeType()
    {
        $this->eZNotificationEventType( self::NOTIFICATION_TYPE_STRING );
    }

    function initializeEvent( $event, $params )
    {
        eZDebugSetting::writeDebug( 'kernel-notification', $params, 'params for type' );
        $time = 0;
        if ( array_key_exists( 'time', $params ) )
        {
            $time = $params['time'];
        }
        else
        {
            $time = time();
        }
        $event->setAttribute( 'data_int1', $time );
    }

    function eventContent( $event )
    {
        $date = new eZDate( );
        $stamp = $event->attribute( 'data_int1' );
        $date->setTimeStamp( $stamp );
        return $date;
    }

}

eZNotificationEventType::register( eZCurrentTimeType::NOTIFICATION_TYPE_STRING, 'eZCurrentTimeType' );


?>
