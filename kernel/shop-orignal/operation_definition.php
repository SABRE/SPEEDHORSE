<?php
//
// Created on: <01-Nov-2002 13:39:10 amos>
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

$OperationList = array();
// This operation is used when a user tries to add an object to the basket
// It will be called from content/add
$OperationList['addtobasket'] = array( 'name' => 'addtobasket',
                                       'default_call_method' => array( 'include_file' => 'kernel/shop/ezshopoperationcollection.php',
                                                                       'class' => 'eZShopOperationCollection' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( array( 'name' => 'object_id',
                                                                     'type' => 'integer',
                                                                     'required' => true ),
                                                              array( 'name' => 'option_list',
                                                                     'type' => 'array',
                                                                     'required' => true ),
                                                              array( 'name' => 'quantity',
                                                                     'type' => 'integer',
                                                                     'required' => false ),
                                                              array( 'name' => 'basket_id',
                                                                     'type' => 'integer',
                                                                     'required' => true ) ),
                                       'keys' => array( 'basket_id', 'object_id' ),
                                       'body' => array( array( 'type' => 'trigger',
                                                               'name' => 'pre_addtobasket',
                                                               'keys' => array( 'object_id' ) ),
                                                        array( 'type' => 'method',
                                                               'name' => 'add-to-basket',
                                                               'frequency' => 'once',
                                                               'method' => 'addToBasket' ),
                                                        array( 'type' => 'method',
                                                               'name' => 'update-shipping-info',
                                                               'frequency' => 'once',
                                                               'method' => 'updateShippingInfo' ),
                                                        array( 'type' => 'trigger',
                                                               'name' => 'post_addtobasket',
                                                               'keys' => array( 'object_id' ) ) ) );

$OperationList['confirmorder'] = array( 'name' => 'confirmorder',
                                        'default_call_method' => array( 'include_file' => 'kernel/shop/ezshopoperationcollection.php',
                                                                        'class' => 'eZShopOperationCollection' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'order_id',
                                                                      'type' => 'integer',
                                                                      'required' => true ) ),
                                        'keys' => array( 'order_id' ),
                                        'body' => array( array( 'type' => 'trigger',
                                                                'name' => 'pre_confirmorder',
                                                                'keys' => array( 'order_id' ) ),
                                                         array( 'type' => 'method',
                                                                'name' => 'handle-user-country',
                                                                'frequency' => 'once',
                                                                'method' => 'handleUserCountry' ),
                                                         array( 'type' => 'method',
                                                                'name' => 'handle-shipping',
                                                                'frequency' => 'once',
                                                                'method' => 'handleShipping' ),
                                                         array( 'type' => 'method',
                                                                'name' => 'fetch-order',
                                                                'frequency' => 'once',
                                                                'method' => 'fetchOrder' ) ) );

$OperationList['updatebasket'] = array( 'name' => 'updatebasket',
                                        'default_call_method' => array( 'include_file' => 'kernel/shop/ezshopoperationcollection.php',
                                                                        'class' => 'eZShopOperationCollection' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'item_count_list',
                                                                      'type' => 'array',
                                                                      'required' => true ),
                                                               array( 'name' => 'item_id_list',
                                                                      'type' => 'array',
                                                                      'required' => true )
                                                              ),
                                        'keys' => array( 'item_count_list', 'item_id_list' ),
                                        'body' => array( array( 'type' => 'trigger',
                                                                'name' => 'pre_updatebasket',
                                                                'keys' => array(  ) ),
                                                         array( 'type' => 'method',
                                                                'name' => 'update-basket',
                                                                'frequency' => 'once',
                                                                'method' => 'updateBasket' ),
                                                         array( 'type' => 'trigger',
                                                                'name' => 'post_updatebasket',
                                                                'keys' => array(  ) ),
                                                        ) );

$OperationList['checkout'] = array( 'name' => 'checkout',
                                    'default_call_method' => array( 'include_file' => 'kernel/shop/ezshopoperationcollection.php',
                                                                    'class' => 'eZShopOperationCollection' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'order_id',
                                                                  'type' => 'integer',
                                                                  'required' => true ) ),
                                    'keys' => array( 'order_id' ),
                                    'body' => array( array( 'type' => 'method',
                                                            'name' => 'check-currency',
                                                            'frequency' => 'once',
                                                            'method' => 'checkCurrency' ),
                                                     array( 'type' => 'trigger',
                                                            'name' => 'pre_checkout',
                                                            'keys' => array( 'order_id' ) ),
                                                     array( 'type' => 'method',
                                                            'name' => 'activate-order',
                                                            'frequency' => 'once',
                                                            'method' => 'activateOrder' ),
                                                     array( 'type' => 'method',
                                                            'name' => 'send-order-email',
                                                            'frequency' => 'once',
                                                            'method' => 'sendOrderEmails' ),
                                                     array( 'type' => 'trigger',
                                                            'name' => 'post_checkout',
                                                            'keys' => array( 'order_id' ) ) ) );
?>
