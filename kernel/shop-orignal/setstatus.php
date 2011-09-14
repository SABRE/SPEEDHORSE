<?php
//
// Created on: <08-Mar-2005 18:16:54 jhe>
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
$user = eZUser::currentUser();

$order = eZOrder::fetch( $OrderID );
if ( !$order )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $http->hasPostVariable( "OrderID" ) && $http->hasPostVariable( "StatusID" ) && $http->hasPostVariable( "SetOrderStatusButton" ) )
{
    $access = $order->canModifyStatus( $StatusID );

    if ( $access )
    {
        if ( $order->attribute( 'status_id' ) != $StatusID )
        {
            $order->modifyStatus( $StatusID );
        }

        if ( $http->hasPostVariable( 'RedirectURI' ) )
        {
            $uri = $http->postVariable( 'RedirectURI' );
            $module->redirectTo( $uri );
            return;
        }
        else
        {
            $module->redirectTo( '/shop/orderview/' . $orderID );
            return;
        }
    }
    else
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

?>
