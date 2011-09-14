<?php
//
// Definition of eZDefaultBasketInfoHandler class
//
// Created on: <09-Nov-2006 11:38:45 bjorn>
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


class eZDefaultBasketInfoHandler
{
    /*!
     Constructor
    */
    function eZDefaultBasketInfoHandler()
    {
    }

    /*!
     Calculate additional information about vat and prices for items in the basket.
    */
    function updatePriceInfo( $productCollectionID, &$basketInfo )
    {
        $shippingInfo = eZShippingManager::getShippingInfo( $productCollectionID );
        $additionalShippingValues = eZShippingManager::vatPriceInfo( $shippingInfo );
        $returnValue = false;
        foreach ( $additionalShippingValues['shipping_vat_list'] as $vatValue => $additionalShippingValueArray )
        {
            $shippingExVAT = $additionalShippingValueArray['shipping_ex_vat'];
            $shippingIncVAT = $additionalShippingValueArray['shipping_inc_vat'];
            $shippingVat = $additionalShippingValueArray['shipping_vat'];

            if ( !isset( $basketInfo['price_info'][$vatValue]['total_price_ex_vat'] ) )
            {
                $basketInfo['price_info'][$vatValue]['total_price_ex_vat'] = $shippingExVAT;
                $basketInfo['price_info'][$vatValue]['total_price_inc_vat'] = $shippingIncVAT;
                $basketInfo['price_info'][$vatValue]['total_price_vat'] = $shippingVat;

                $basketInfo['total_price_info']['total_price_ex_vat'] = $shippingExVAT;
                $basketInfo['total_price_info']['total_price_inc_vat'] = $shippingIncVAT;
                $basketInfo['total_price_info']['total_price_vat'] = $shippingVat;
            }
            else
            {
                $basketInfo['price_info'][$vatValue]['total_price_ex_vat'] += $shippingExVAT;
                $basketInfo['price_info'][$vatValue]['total_price_inc_vat'] += $shippingIncVAT;
                $basketInfo['price_info'][$vatValue]['total_price_vat'] += $shippingVat;

                $basketInfo['total_price_info']['total_price_ex_vat'] += $shippingExVAT;
                $basketInfo['total_price_info']['total_price_inc_vat'] += $shippingIncVAT;
                $basketInfo['total_price_info']['total_price_vat'] += $shippingVat;
            }

            if ( !isset( $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_ex_vat'] ) )
            {
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_ex_vat'] = $shippingExVAT;
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_inc_vat'] = $shippingIncVAT;
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_vat'] = ( $shippingIncVAT - $shippingExVAT );
            }
            else
            {
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_ex_vat'] += $shippingExVAT;
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_inc_vat'] += $shippingIncVAT;
                $basketInfo['additional_info']['shipping_items'][$vatValue]['total_price_vat'] += ( $shippingIncVAT - $shippingExVAT );
            }

            if ( !isset( $basketInfo['additional_info']['shipping_total']['total_price_ex_vat'] ) )
            {
                $basketInfo['additional_info']['shipping_total']['total_price_ex_vat'] = $shippingExVAT;
                $basketInfo['additional_info']['shipping_total']['total_price_inc_vat'] = $shippingIncVAT;
                $basketInfo['additional_info']['shipping_total']['total_price_vat'] = ( $shippingIncVAT - $shippingExVAT );
            }
            else
            {
                $basketInfo['additional_info']['shipping_total']['total_price_ex_vat'] += $shippingExVAT;
                $basketInfo['additional_info']['shipping_total']['total_price_inc_vat'] += $shippingIncVAT;
                $basketInfo['additional_info']['shipping_total']['total_price_vat'] += ( $shippingIncVAT - $shippingExVAT );
            }
        }

        if ( count( $additionalShippingValues['shipping_vat_list'] ) > 0 )
        {
            $returnValue = true;
        }

        return $returnValue;
    }
}

?>
