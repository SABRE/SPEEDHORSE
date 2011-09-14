<?php
//
// Definition of eZNotificationTransport class
//
// Created on: <13-May-2003 12:01:34 sp>
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
  \class eZNotificationTransport eznotificationtransport.php
  \brief The class eZNotificationTransport does

*/
class eZNotificationTransport
{
    /*!
     Constructor
    */
    function eZNotificationTransport()
    {
    }

    /**
     * Returns a shared instance of the eZNotificationTransport class.
     *
     *
     * @param string|false $transport Uses notification.ini[TransportSettings]DefaultTransport if false
     * @param bool $forceNewInstance
     * @return eZNotificationTransport
     */
    static function instance( $transport = false, $forceNewInstance = false )
    {
        $ini = eZINI::instance( 'notification.ini' );
        if ( $transport == false )
        {
            $transport = $ini->variable( 'TransportSettings', 'DefaultTransport' );
        }
        $transportImpl =& $GLOBALS['eZNotificationTransportGlobalInstance_' . $transport ];
        $class = $transportImpl !== null ? strtolower( get_class( $transportImpl ) ) : '';

        $fetchInstance = false;
        if ( !preg_match( '/.*?transport/', $class ) )
                $fetchInstance = true;

        if ( $forceNewInstance  )
        {
            $fetchInstance = true;
        }

        if ( $fetchInstance )
        {
            $extraPluginPathArray = $ini->variable( 'TransportSettings', 'TransportPluginPath' );
            $pluginPathArray = array_merge( array( 'kernel/classes/notification/' ),
                                            $extraPluginPathArray );
            foreach( $pluginPathArray as $pluginPath )
            {
                $transportFile = $pluginPath . $transport . 'notificationtransport.php';
                if ( file_exists( $transportFile ) )
                {
                    include_once( $transportFile );
                    $className = $transport . 'notificationtransport';
                    $impl = new $className( );
                    break;
                }
            }
        }
        if ( !isset( $impl ) )
        {
            $impl = new eZNotificationTransport();
            eZDebug::writeError( 'Transport implementation not supported: ' . $transport, __METHOD__ );
        }
        return $impl;
    }

    function send( $address = array(), $subject, $body, $transportData = null )
    {
        return true;
    }
}

?>
