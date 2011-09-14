<?php
//
// Created on: <06-Oct-2002 16:01:10 amos>
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
$FunctionList['participant'] = array( 'name' => 'participant',
                                      'operation_types' => array( 'read' ),
                                      'call_method' => array( 'class' => 'eZCollaborationFunctionCollection',
                                                              'method' => 'fetchParticipant' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array( array( 'name' => 'item_id',
                                                                    'required' => true,
                                                                    'default' => false ),
                                                             array( 'name' => 'participant_id',
                                                                    'required' => false,
                                                                    'default' => false ) ) );
$FunctionList['participant_list'] = array( 'name' => 'participant_list',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'eZCollaborationFunctionCollection',
                                                                   'method' => 'fetchParticipantList' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( array( 'name' => 'item_id',
                                                                         'required' => false,
                                                                         'default' => false ),
                                                                  array( 'name' => 'sort_by',
                                                                         'required' => false,
                                                                         'default' => false ),
                                                                  array( 'name' => 'offset',
                                                                         'required' => false,
                                                                         'default' => false ),
                                                                  array( 'name' => 'limit',
                                                                         'required' => false,
                                                                         'default' => false ) ) );
$FunctionList['participant_map'] = array( 'name' => 'participant_map',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'class' => 'eZCollaborationFunctionCollection',
                                                                  'method' => 'fetchParticipantMap' ),
                                           'parameter_type' => 'standard',
                                          'parameters' => array( array( 'name' => 'item_id',
                                                                        'required' => false,
                                                                        'default' => false ),
                                                                 array( 'name' => 'sort_by',
                                                                        'required' => false,
                                                                        'default' => false ),
                                                                 array( 'name' => 'offset',
                                                                        'required' => false,
                                                                        'default' => false ),
                                                                 array( 'name' => 'limit',
                                                                        'required' => false,
                                                                        'default' => false ),
                                                                 array( 'name' => 'field',
                                                                        'required' => false,
                                                                        'default' => false ) ) );
$FunctionList['message_list'] = array( 'name' => 'message_list',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZCollaborationFunctionCollection',
                                                               'method' => 'fetchMessageList' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( array( 'name' => 'item_id',
                                                                     'required' => true,
                                                                     'default' => false ),
                                                              array( 'name' => 'sort_by',
                                                                     'required' => false,
                                                                     'default' => false ),
                                                              array( 'name' => 'offset',
                                                                     'required' => false,
                                                                     'default' => false ),
                                                              array( 'name' => 'limit',
                                                                     'required' => false,
                                                                     'default' => false ) ) );
$FunctionList['item_list'] = array( 'name' => 'item_list',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'class' => 'eZCollaborationFunctionCollection',
                                                            'method' => 'fetchItemList' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'sort_by',
                                                                  'required' => false,
                                                                  'default' => false ),
                                                           array( 'name' => 'offset',
                                                                  'required' => false,
                                                                  'default' => false ),
                                                           array( 'name' => 'limit',
                                                                  'required' => false,
                                                                  'default' => false ),
                                                           array( 'name' => 'status',
                                                                  'required' => false,
                                                                  'default' => false ),
                                                           array( 'name' => 'is_read',
                                                                  'required' => false,
                                                                  'default' => null ),
                                                           array( 'name' => 'is_active',
                                                                  'required' => false,
                                                                  'default' => null ),
                                                           array( 'name' => 'parent_group_id',
                                                                  'required' => false,
                                                                  'default' => null ) ) );
$FunctionList['item_count'] = array( 'name' => 'item_count',
                                     'operation_types' => array( 'read' ),
                                     'call_method' => array( 'class' => 'eZCollaborationFunctionCollection',
                                                             'method' => 'fetchItemCount' ),
                                     'parameter_type' => 'standard',
                                     'parameters' => array( array( 'name' => 'is_read',
                                                                   'required' => false,
                                                                   'default' => null ),
                                                            array( 'name' => 'is_active',
                                                                   'required' => false,
                                                                   'default' => null ),
                                                            array( 'name' => 'parent_group_id',
                                                                   'required' => false,
                                                                   'default' => null ),
                                                            array( 'name' => 'status',
                                                                   'required' => false,
                                                                   'default' => false ) ) );
$FunctionList['group_tree'] = array( 'name' => 'group_tree',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'class' => 'eZCollaborationFunctionCollection',
                                                       'method' => 'fetchGroupTree' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( array( 'name' => 'parent_group_id',
                                                             'required' => true ),
                                                      array( 'name' => 'sort_by',
                                                             'required' => false,
                                                             'default' => false ),
                                                      array( 'name' => 'offset',
                                                             'required' => false,
                                                             'default' => false ),
                                                      array( 'name' => 'limit',
                                                             'required' => false,
                                                             'default' => false ),
                                                      array( 'name' => 'depth',
                                                             'required' => false,
                                                             'default' => false ) ) );

$FunctionList['tree_count'] = array( 'name' => 'tree_count',
                                     'operation_types' => array( 'read' ),
                                     'call_method' => array( 'class' => 'eZContentFunctionCollection',
                                                             'method' => 'fetchObjectTreeCount' ),
                                     'parameter_type' => 'standard',
                                     'parameters' => array( array( 'name' => 'parent_node_id',
                                                                   'required' => true ),
                                                            array( 'name' => 'class_filter_type',
                                                                   'required' => false,
                                                                   'default' => false ),
                                                            array( 'name' => 'class_filter_array',
                                                                   'required' => false,
                                                                   'default' => false ),
                                                            array( 'name' => 'depth',
                                                                   'required' => false,
                                                                   'default' => 0 ) ) );

?>
