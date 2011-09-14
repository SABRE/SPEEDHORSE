<?php
//
// Created on: <08-Aug-2006 15:23:10 bjorn>
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
  \class eZDefaultConfirmOrderHandler ezdefaultconfirmorderhandler.php
  \brief The class eZDefaultConfirmOrderHandler does

*/

class eZDefaultConfirmOrderHandler
{
    /*!
     Constructor
    */
    function eZDefaultConfirmOrderHandler()
    {
    }

    function execute( $params = array() )
    {
        $ini = eZINI::instance();
        $sendOrderEmail = $ini->variable( 'ShopSettings', 'SendOrderEmail' );
        if ( $sendOrderEmail == 'enabled' )
        {
            $this->sendOrderEmail( $params );
        }
    }

    function sendOrderEmail( $params )
    {
        $ini = eZINI::instance();
        if ( isset( $params['order'] ) and
             isset( $params['email'] ) )
        {
            $order = $params['order'];
            $email = $params['email'];

            $tpl = eZTemplate::factory();
            $tpl->setVariable( 'order', $order );
            $templateResult = $tpl->fetch( 'design:shop/orderemail.tpl' );

            $subject = $tpl->variable( 'subject' );

            $mail = new eZMail();

            $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
            if ( !$emailSender )
                $emailSender = $ini->variable( "MailSettings", "AdminEmail" );

            if ( $tpl->hasVariable( 'content_type' ) )
                $mail->setContentType( $tpl->variable( 'content_type' ) );

            $mail->setReceiver( $email );
            $mail->setSender( $emailSender );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );

            $email = $ini->variable( 'MailSettings', 'AdminEmail' );

            $mail = new eZMail();

            if ( $tpl->hasVariable( 'content_type' ) )
                $mail->setContentType( $tpl->variable( 'content_type' ) );

            $mail->setReceiver( $email );
            $mail->setSender( $emailSender );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            $mailResult = eZMailTransport::send( $mail );
        }
    }
}

?>
