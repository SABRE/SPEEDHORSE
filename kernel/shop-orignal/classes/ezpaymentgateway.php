<?php
//
// Definition of eZPaymentGateway class
//
// Created on: <18-May-2004 14:18:58 dl>
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
  \class eZPaymentGateway ezpaymentgateway.php
  \brief Abstract class for all payment gateways.
*/

class eZPaymentGateway
{
    /*!
    Constructor.
    */
    function eZPaymentGateway()
    {
        $this->logger = eZPaymentLogger::CreateForAdd( "var/log/eZPaymentGateway.log" );
    }

    function execute( $process, $event )
    {
        $this->logger->writeTimedString( 'You must override this function.', 'execute' );
        return eZWorkflowType::STATUS_REJECTED;
    }

    function needCleanup()
    {
        return false;
    }

    function cleanup( $process, $event )
    {
    }

    /*!
    Creates short description of order. Usually this string is
    passed to payment site as describtion of payment.
    */
    function createShortDescription( $order, $maxDescLen )
    {
        //__DEBUG__
        $this->logger->writeTimedString("createShortDescription");
        //___end____

        $descText       = '';
        $productItems   = $order->productItems();

        foreach( $productItems as $item )
        {
            $descText .= $item['object_name'] . ',';
        }
        $descText   = rtrim( $descText, "," );

        $descLen    = strlen( $descText );
        if( ($maxDescLen > 0) && ($descLen > $maxDescLen) )
        {
            $descText = substr($descText, 0, $maxDescLen - 3) ;
            $descText .= '...';
        }

        //__DEBUG__
        $this->logger->writeTimedString("descText=$descText");
        //___end____

        return $descText;
    }

    public $logger;
}
?>
