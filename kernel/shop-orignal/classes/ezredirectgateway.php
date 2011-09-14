<?php
//
// Definition of eZRedirectGateway class
//
// Created on: <11-Jun-2004 14:18:58 dl>
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
  \class eZRedirectGateway ezredirectgateway.php
  \brief The class eZRedirectGateway is a
  base class for payment gateways which
  support payment through redirection to
  the payment site and payment notifications
  throught callbacks(postbacks).
*/

class eZRedirectGateway extends eZPaymentGateway
{
    const OBJECT_NOT_CREATED = 1;
    const OBJECT_CREATED = 2;

    /*!
    Constructor.
    */
    function eZRedirectGateway()
    {
        //__DEBUG__
        $this->logger   = eZPaymentLogger::CreateForAdd( "var/log/eZRedirectGateway.log" );
        $this->logger->writeTimedString( 'eZRedirectGateway::eZRedirectGateway()' );
        //___end____
    }

    function execute( $process, $event )
    {
        //__DEBUG__
        $this->logger->writeTimedString("execute");
        //___end____

        $processParameters = $process->attribute( 'parameter_list' );
        $processID         = $process->attribute( 'id' );

        switch ( $process->attribute( 'event_state' ) )
        {
            case self::OBJECT_CREATED:
            {
                //__DEBUG__
                $this->logger->writeTimedString("case eZRedirectGateway::OBJECT_CREATED");
                //___end____

                $thePayment = eZPaymentObject::fetchByProcessID( $processID );
                if ( is_object( $thePayment ) && $thePayment->approved() )
                {
                    //__DEBUG__
                    $this->logger->writeTimedString("Payment accepted.");
                    //___end____
                    return eZWorkflowType::STATUS_ACCEPTED;
                }
                //__DEBUG__
                else
                {
                    $this->logger->writeTimedString("Error. Payment rejected: unable to fetch 'eZPaymentObject' or payment status 'not approved'");
                }
                //___end____

                return eZWorkflowType::STATUS_REJECTED;
            }break;

            case self::OBJECT_NOT_CREATED:
                //__DEBUG__
                $this->logger->writeTimedString("case eZRedirectGateway::OBJECT_NOT_CREATED");
                //___end____

            default:
            {
                $orderID        = $processParameters['order_id'];
                $paymentObject  = $this->createPaymentObject( $processID, $orderID );

                if( is_object( $paymentObject ) )
                {
                    $paymentObject->store();
                    $process->setAttribute( 'event_state', self::OBJECT_CREATED );

                    $process->RedirectUrl = $this->createRedirectionUrl( $process );
                }
                else
                {
                    //__DEBUG__
                    $this->logger->writeTimedString("Unable to create 'eZPaymentObject'. Payment rejected.");
                    //___end____
                    return eZWorkflowType::STATUS_REJECTED;
                }
            }break;
        }

        //__DEBUG__
        $this->logger->writeTimedString("return eZWorkflowType::STATUS_REDIRECT_REPEAT");
        //___end____
        return eZWorkflowType::STATUS_REDIRECT_REPEAT;
    }

    function needCleanup()
    {
        return true;
    }

    /*!
    Removes temporary eZPaymentObject from database.
    */
    function cleanup( $process, $event )
    {
        //__DEBUG__
        $this->logger->writeTimedString("cleanup");
        //___end____

        $paymentObj = eZPaymentObject::fetchByProcessID( $process->attribute( 'id' ) );

        if ( is_object( $paymentObj ) )
        {
            $paymentObj->remove();
        }
    }

    /*!
    Creates instance of subclass of eZPaymentObject which stores
    information about payment processing(orderID, workflowID, ...).
    Must be overridden in subclass.
    */
    function createPaymentObject( $processID, $orderID )
    {
        //__DEBUG__
        $this->logger->writeTimedString("createPaymentObject. You have to override this");
        //___end____
        return null;
    }

    /*!
    Creates redirection url to payment site.
    Must be overridden in subclass.
    */
    function createRedirectionUrl( $process )
    {
        //__DEBUG__
        $this->logger->writeTimedString("createRedirectionUrl. You have to override this");
        //___end____
        return null;
    }
}

?>
