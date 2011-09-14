<?php
//
// Definition of Removeorder class
//
// Created on: <03-Mar-2004 09:45:38 wy>
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


$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$deleteIDArray = $http->sessionVariable( "DeleteOrderIDArray" );

if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    $db = eZDB::instance();
    $db->begin();
    foreach ( $deleteIDArray as $deleteID )
    {
        eZOrder::cleanupOrder( $deleteID );
    }
    $db->commit();
    $Module->redirectTo( '/shop/orderlist/' );
}
elseif ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/shop/orderlist/' );
}
else // no action yet: just displaying the template
{
    $orderNumbersArray = array();
    foreach ( $deleteIDArray as $orderID )
    {
        $order = eZOrder::fetch( $orderID );
        if ( $order === null )
            continue;   // just to prevent possible fatal error below

        $orderNumbersArray[] = $order->attribute( 'order_nr' );
    }
    $orderNumbersString = implode( ', ', $orderNumbersArray );

    $Module->setTitle( ezpI18n::tr( 'shop', 'Remove orders' ) );

    $tpl = eZTemplate::factory();
    $tpl->setVariable( "module", $Module );
    $tpl->setVariable( "delete_result", $orderNumbersString );
    $Result = array();

    $Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/shop', 'Remove order' ),
                                    'url' => false ) );
    $Result['content'] = $tpl->fetch( "design:shop/removeorder.tpl" );
}
?>
