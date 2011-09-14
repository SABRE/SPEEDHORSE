<?php
//
// Created on: <07-���-2003 14:21:36 sp>
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
$http = eZHTTPTool::instance();
$module = $Params['Module'];

$orderID = $http->sessionVariable( 'MyTemporaryOrderID' );
$order = eZOrder::fetch( $orderID );

if ( $order instanceof eZOrder )
{
    if (  $order->attribute( 'is_temporary' ) )
    {
        $paymentObj = eZPaymentObject::fetchByOrderID( $orderID );
        if ( $paymentObj != null )
        {
            $startTime = time();
            while( ( time() - $startTime ) < 25 )
            {
                eZDebug::writeDebug( "next iteration", "checkout" );
                $order = eZOrder::fetch( $orderID );
                if ( $order->attribute( 'is_temporary' ) == 0 )
                {
                    break;
                }
                else
                {
                    sleep ( 2 );
                }
            }
        }
        $order = eZOrder::fetch( $orderID );
        if (  $order->attribute( 'is_temporary' ) == 1 && $paymentObj == null  )
        {
            $email = $order->accountEmail();
            $order->setAttribute( 'email', $email );
            $order->store();

            $http->setSessionVariable( "UserOrderID", $order->attribute( 'id' ) );

            $operationResult = eZOperationHandler::execute( 'shop', 'checkout', array( 'order_id' => $order->attribute( 'id' ) ) );
            switch( $operationResult['status'] )
            {
                case eZModuleOperationInfo::STATUS_HALTED:
                case eZModuleOperationInfo::STATUS_REPEAT:
                {
                    if (  isset( $operationResult['redirect_url'] ) )
                    {
                        $module->redirectTo( $operationResult['redirect_url'] );
                        return;
                    }
                    else if ( isset( $operationResult['result'] ) )
                    {
                        $result = $operationResult['result'];
                        $resultContent = false;
                        if ( is_array( $result ) )
                        {
                            if ( isset( $result['content'] ) )
                            {
                                $resultContent = $result['content'];
                            }
                            if ( isset( $result['path'] ) )
                            {
                                $Result['path'] = $result['path'];
                            }
                        }
                        else
                            $resultContent = $result;
                        $Result['content'] = $resultContent;
                        return;
                    }
                }break;
                case eZModuleOperationInfo::STATUS_CANCELLED:
                {
                    $Result = array();

                    $tpl = eZTemplate::factory();
                    $tpl->setVariable( 'operation_result', $operationResult );

                    $Result['content'] = $tpl->fetch( "design:shop/cancelcheckout.tpl" ) ;
                    $Result['path'] = array( array( 'url' => false,
                                                    'text' => ezpI18n::tr( 'kernel/shop', 'Checkout' ) ) );

                    return;
                }

            }
        }
        else
        {
            if ( $order->attribute( 'is_temporary' ) == 0 )
            {
                $http->removeSessionVariable( "CheckoutAttempt" );
                $module->redirectTo( '/shop/orderview/' . $orderID );
                return;
            }
            else
            {
                // Get the attempt number and the order.
                $attempt = ($http->hasSessionVariable("CheckoutAttempt") ? $http->sessionVariable("CheckoutAttempt") : 0 );
                $attemptOrderID = ($http->hasSessionVariable("CheckoutAttemptOrderID") ? $http->sessionVariable("CheckoutAttemptOrderID") : 0 );

                // This attempt is for another order. So reset the attempt.
                if ($attempt != 0 && $attemptOrderID != $orderID) $attempt = 0;

                $http->setSessionVariable("CheckoutAttempt", ++$attempt);
                $http->setSessionVariable("CheckoutAttemptOrderID", $orderID);

                if ( $attempt < 4)
                {
                    $Result = array();

                    $tpl = eZTemplate::factory();
                    $tpl->setVariable( 'attempt', $attempt );
                    $tpl->setVariable( 'orderID', $orderID );
                    $Result['content'] = $tpl->fetch( "design:shop/checkoutagain.tpl" ) ;
                    $Result['path'] = array( array( 'url' => false,
                                                    'text' => ezpI18n::tr( 'kernel/shop', 'Checkout' ) ) );
                    return;
                }
                else
                {
                    // Got no receipt or callback from the payment server.
                    $http->removeSessionVariable( "CheckoutAttempt" );

                    $Result = array();

                    $tpl = eZTemplate::factory();
                    $tpl->setVariable ("ErrorCode", "NO_CALLBACK");
                    $tpl->setVariable ("OrderID", $orderID);

                    $Result['content'] = $tpl->fetch( "design:shop/cancelcheckout.tpl" ) ;
                    $Result['path'] = array( array( 'url' => false,
                                                    'text' => ezpI18n::tr( 'kernel/shop', 'Checkout' ) ) );
                    return;
                }
            }
        }
   }
   $module->redirectTo( '/shop/orderview/' . $orderID );
   return;

}

?>
