<?php
//
// Created on: <04-Dec-2002 16:15:49 bf>
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

$http = eZHTTPTool::instance();
$module = $Params['Module'];


$tpl = eZTemplate::factory();
$tpl->setVariable( "module_name", 'shop' );

$orderID = $http->sessionVariable( 'MyTemporaryOrderID' );

$order = eZOrder::fetch( $orderID );
if ( !is_object( $order ) )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( $order instanceof eZOrder )
{
    if ( $http->hasPostVariable( "ConfirmOrderButton" ) )
    {
        $order->detachProductCollection();
        $ini = eZINI::instance();
        if ( $ini->variable( 'ShopSettings', 'ClearBasketOnCheckout' ) == 'enabled' )
        {
            $basket = eZBasket::currentBasket();
            $basket->remove();
        }
        $module->redirectTo( '/shop/checkout/' );
        return;
    }

    if ( $http->hasPostVariable( "CancelButton" ) )
    {
        $order->purge( /*$removeCollection = */ false );
        $module->redirectTo( '/shop/basket/' );
        return;
    }

    $tpl->setVariable( "order", $order );
}

$basket = eZBasket::currentBasket();
$basket->updatePrices();

$operationResult = eZOperationHandler::execute( 'shop', 'confirmorder', array( 'order_id' => $order->attribute( 'id' ) ) );

switch( $operationResult['status'] )
{
    case eZModuleOperationInfo::STATUS_CONTINUE:
    {
        if ( $operationResult != null &&
             !isset( $operationResult['result'] ) &&
             ( !isset( $operationResult['redirect_url'] ) || $operationResult['redirect_url'] == null ) )
        {
            $order = eZOrder::fetch( $order->attribute( 'id' ) );
            $tpl->setVariable( "order", $order );

            $Result = array();
            $Result['content'] = $tpl->fetch( "design:shop/confirmorder.tpl" );
            $Result['path'] = array( array( 'url' => false,
                                            'text' => ezpI18n::tr( 'kernel/shop', 'Confirm order' ) ) );
        }
    }break;

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
            {
                $resultContent = $result;
            }
            $Result['content'] = $resultContent;
        }
    }break;
    case eZModuleOperationInfo::STATUS_CANCELLED:
    {
        $Result = array();
        if ( isset( $operationResult['result']['content'] ) )
            $Result['content'] = $operationResult['result']['content'];
        else
            $Result['content'] = ezpI18n::tr( 'kernel/shop', "The confirm order operation was canceled. Try to checkout again." );

        $Result['path'] = array( array( 'url' => false,
                                        'text' => ezpI18n::tr( 'kernel/shop', 'Confirm order' ) ) );
    }

}

/*
$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/confirmorder.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/shop', 'Confirm order' ) ) );
*/
?>
