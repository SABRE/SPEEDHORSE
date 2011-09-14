<?php
//
// Created on: <14-May-2003 16:37:37 sp>
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

$FunctionList = array();
$FunctionList['handler_list'] = array( 'name' => 'handler_list',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                               'method' => 'handlerList' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( ) );

$FunctionList['digest_handlers'] = array( 'name' => 'digest_handlers',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                                  'method' => 'digestHandlerList' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array( array( 'name' => 'date',
                                                                        'type' => 'integer',
                                                                        'required' => true ),
                                                                 array( 'name' => 'address',
                                                                        'type' => 'string',
                                                                        'required' => true ) ) );


$FunctionList['digest_items'] = array( 'name' => 'digest_items',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                               'method' => 'digestItems' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( array( 'name' => 'date',
                                                                     'type' => 'integer',
                                                                     'required' => true ),
                                                              array( 'name' => 'address',
                                                                     'type' => 'string',
                                                                     'required' => true ),
                                                              array( 'name' => 'handler',
                                                                     'type' => 'string',
                                                                     'required' => true ) ) );


$FunctionList['event_content'] = array( 'name' => 'event_content',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                               'method' => 'eventContent' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( array( 'name' => 'event_id',
                                                                     'type' => 'integer',
                                                                     'required' => true ) ) );

$FunctionList['subscribed_nodes'] = array( 'name' => 'subscribed_nodes',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                                   'method' => 'subscribedNodes' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( array( 'name' => 'offset',
                                                                         'type' => 'integer',
                                                                         'default' => false,
                                                                         'required' => false ),
                                                                  array( 'name' => 'limit',
                                                                         'type' => 'integer',
                                                                         'default' => false,
                                                                         'required' => false ) ) );

$FunctionList['subscribed_nodes_count'] = array( 'name' => 'subscribed_nodes_count',
                                                 'operation_types' => array( 'read' ),
                                                 'call_method' => array( 'class' => 'eZNotificationFunctionCollection',
                                                                         'method' => 'subscribedNodesCount' ),
                                                 'parameter_type' => 'standard',
                                                 'parameters' => array() );

?>
