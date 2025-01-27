<?php
//
// Definition of eZSendmailTransport class
//
// Created on: <10-Dec-2002 14:41:22 amos>
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
  \class eZSendmailTransport ezsendmailtransport.php
  \brief Sends the email message to sendmail which takes care of sending the actual message.

  Uses the mail() function in PHP to pass the email to the sendmail system.

*/

class eZSendmailTransport extends eZMailTransport
{
    /*!
     Constructor
    */
    function eZSendmailTransport()
    {
    }

    function sendMail( eZMail $mail )
    {
        $ini = eZINI::instance();
        $sendmailOptions = '';
        $emailFrom = $mail->sender();
        $emailSender = isset( $emailFrom['email'] ) ? $emailFrom['email'] : false;
        if ( !$emailSender || count( $emailSender) <= 0 )
            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
        if ( !$emailSender )
            $emailSender = $ini->variable( 'MailSettings', 'AdminEmail' );
        if ( !eZMail::validate( $emailSender ) )
            $emailSender = false;

        $isSafeMode = ini_get( 'safe_mode' ) != 0;

        $sendmailOptionsArray = $ini->variable( 'MailSettings', 'SendmailOptions' );
        if( is_array($sendmailOptionsArray) )
            $sendmailOptions = implode( ' ', $sendmailOptionsArray );
        elseif( !is_string($sendmailOptionsArray) )
            $sendmailOptions = $sendmailOptionsArray;
        if ( !$isSafeMode and
             $emailSender )
            $sendmailOptions .= ' -f'. $emailSender;

        if ( $isSafeMode and
             $emailSender and
             $mail->sender() == false )
            $mail->setSenderText( $emailSender );

        if( function_exists( 'mail' ) )
        {
            $message = $mail->body();
            $sys = eZSys::instance();
            $excludeHeaders = array( 'Subject' );
            // If not Windows PHP mail() implementation, we can not specify a To: header in the $additional_headers parameter,
            // because then there will be 2 To: headers in the resulting e-mail.
            // However, we can use "undisclosed-recipients:;" in $to.
            if ( $sys->osType() != 'win32' )
            {
                $excludeHeaders[] = 'To';
                $receiverEmailText = count( $mail->ReceiverElements ) > 0 ? $mail->receiverEmailText() : 'undisclosed-recipients:;';
            }
            // If Windows PHP mail() implementation, we can specify a To: header in the $additional_headers parameter,
            // it will be used as the only To: header.
            // We can not use "undisclosed-recipients:;" in $to, it will result in a SMTP server response: 501 5.1.3 Bad recipient address syntax
            else
            {
                $receiverEmailText = $mail->receiverEmailText();
            }

            // If in debug mode, send to debug email address and nothing else
            if ( $ini->variable( 'MailSettings', 'DebugSending' ) == 'enabled' )
            {
                $receiverEmailText = $ini->variable( 'MailSettings', 'DebugReceiverEmail' );
                $excludeHeaders[] = 'To';
                $excludeHeaders[] = 'Cc';
                $excludeHeaders[] = 'Bcc';
            }

            $extraHeaders = $mail->headerText( array( 'exclude-headers' => $excludeHeaders ) );

            $returnedValue = mail( $receiverEmailText, $mail->subject(), $message, $extraHeaders, $sendmailOptions );
            if ( $returnedValue === false )
            {
                eZDebug::writeError( 'An error occurred while sending e-mail. Check the Sendmail error message for further information (usually in /var/log/messages)',
                                     __METHOD__ );
            }

            return $returnedValue;
        }
        else
        {
            eZDebug::writeWarning( "Unable to send mail: 'mail' function is not compiled into PHP.", __METHOD__ );
        }

        return false;
    }
}

?>
