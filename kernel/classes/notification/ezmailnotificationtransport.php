<?php
//
// Definition of eZMailNotificationTransport class
//
// Created on: <13-May-2003 13:22:20 sp>
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
  \class eZMailNotificationTransport ezmailnotificationtransport.php
  \brief The class eZMailNotificationTransport does

*/

class eZMailNotificationTransport extends eZNotificationTransport
{
    /*!
     Constructor
    */
    function eZMailNotificationTransport()
    {
        $this->eZNotificationTransport();
    }

    function send( $addressList = array(), $subject, $body, $transportData = null, $parameters = array() )
    {
        $ini = eZINI::instance();
        $mail = new eZMail();
        $addressList = $this->prepareAddressString( $addressList, $mail );

        if ( $addressList == false )
        {
            eZDebug::writeError( 'Error with receiver', __METHOD__ );
            return false;
        }

        $notificationINI = eZINI::instance( 'notification.ini' );
        $emailSender = $notificationINI->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( "MailSettings", "AdminEmail" );

        foreach ( $addressList as $addressItem )
        {
            $mail->extractEmail( $addressItem, $email, $name );
            $mail->addBcc( $email, $name );
        }
        $mail->setSender( $emailSender );
        $mail->setSubject( $subject );
        $mail->setBody( $body );
        if ( isset( $parameters['message_id'] ) )
            $mail->addExtraHeader( 'Message-ID', $parameters['message_id'] );
        if ( isset( $parameters['references'] ) )
            $mail->addExtraHeader( 'References', $parameters['references'] );
        if ( isset( $parameters['reply_to'] ) )
            $mail->addExtraHeader( 'In-Reply-To', $parameters['reply_to'] );
        if ( isset( $parameters['from'] ) )
            $mail->setSenderText( $parameters['from'] );
        if ( isset( $parameters['content_type'] ) )
            $mail->setContentType( $parameters['content_type'] );
        $mailResult = eZMailTransport::send( $mail );
        return $mailResult;
    }


    function prepareAddressString( $addressList, $mail )
    {
        if ( is_array( $addressList ) )
        {
            $validatedAddressList = array();
            foreach ( $addressList as $address )
            {
                if ( $mail->validate( $address ) )
                {
                    $validatedAddressList[] = $address;
                }
            }
//             $addressString = '';
//             if ( count( $validatedAddressList ) > 0 )
//             {
//                 $addressString = implode( ',', $validatedAddressList );
//                 return $addressString;
//             }
            return $validatedAddressList;
        }
        else if ( strlen( $addressList ) > 0 )
        {
            if ( $mail->validate( $addressList ) )
            {
                return $addressList;
            }
        }
        return false;
    }
}

?>
