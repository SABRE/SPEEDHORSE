<?php
//
// Created on: <07-Nov-2005 18:07:10 jhe>
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


$module = $Params['Module'];
$http = eZHTTPTool::instance();
$messages = array();

if ( $http->hasPostVariable( "SaveOrderStatusButton" ) or
     $http->hasPostVariable( "AddOrderStatusButton" ) or
     $http->hasPostVariable( "RemoveOrderStatusButton" ) )
{
    $orderStatusArray = eZOrderStatus::fetchList( true, true );
    foreach ( $orderStatusArray as $orderStatus )
    {
        $id = $orderStatus->attribute( 'id' );
        if ( $http->hasPostVariable( "orderstatus_name_" . $id ) )
        {
            $orderStatus->setAttribute( 'name', $http->postVariable( "orderstatus_name_" . $id ) );
        }
        // Only check the checkbox value if the has_input variable is set
        if ( $http->hasPostVariable( "orderstatus_active_has_input_" . $id ) )
        {
            $orderStatus->setAttribute( 'is_active', $http->hasPostVariable( "orderstatus_active_" . $id ) ? 1: 0 );
        }
        $orderStatus->sync();
    }

    eZOrderStatus::flush();
}

if ( $http->hasPostVariable( "AddOrderStatusButton" ) )
{
    $orderStatus = eZOrderStatus::create();
    $orderStatus->storeCustom();
    $messages[] = array( 'description' => ezpI18n::tr( 'kernel/shop', 'New order status was successfully added.' ) );
}

if ( $http->hasPostVariable( "SaveOrderStatusButton" ) )
{
    $messages[] = array( 'description' => ezpI18n::tr( 'kernel/shop', 'Changes to order status were successfully stored.' ) );
}

if ( $http->hasPostVariable( "RemoveOrderStatusButton" ) )
{
    $orderStatusIDList = array();
    if ( $http->hasPostVariable( 'orderStatusIDList' ) )
        $orderStatusIDList = $http->postVariable( "orderStatusIDList" );

    $hasRemoved = false;
    $triedRemoveInternal = false;
    foreach ( $orderStatusIDList as $orderStatusID )
    {
        $status = eZOrderStatus::fetch( $orderStatusID );
        // Internal status items must not be removed
        if ( $status->isInternal() )
        {
            $triedRemoveInternal = true;
            continue;
        }
        $status->removeThis();
        $hasRemoved = true;
    }
    if ( $hasRemoved )
        $messages[] = array( 'description' => ezpI18n::tr( 'kernel/shop', 'Selected order statuses were successfully removed.' ) );
    if ( $triedRemoveInternal )
        $messages[] = array( 'description' => ezpI18n::tr( 'kernel/shop', 'Internal orders cannot be removed.' ) );
}

$orderStatusArray = eZOrderStatus::fetchList( true, true );

$tpl = eZTemplate::factory();
$tpl->setVariable( "orderstatus_array", $orderStatusArray );
$tpl->setVariable( "module", $module );
$tpl->setVariable( "messages", $messages );

$path = array();
$path[] = array( 'text' => ezpI18n::tr( 'kernel/shop', 'Order list' ),
                 'url' => 'shop/orderlist' );
$path[] = array( 'text' => ezpI18n::tr( 'kernel/shop', 'Status' ),
                 'url' => false );

$Result = array();
$Result['path'] = $path;
$Result['content'] = $tpl->fetch( "design:shop/status.tpl" );

?>
