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
$FunctionList['current_user'] = array( 'name' => 'current_user',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                               'method' => 'fetchCurrentUser' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array() );

$FunctionList['is_logged_in'] = array( 'name' => 'is_logged_in',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                               'method' => 'fetchIsLoggedIn' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( array( 'name' => 'user_id',
                                                                     'type' => 'integer',
                                                                     'required' => true ) ) );

$FunctionList['logged_in_count'] = array( 'name' => 'logged_in_count',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                                  'method' => 'fetchLoggedInCount' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array() );

$FunctionList['anonymous_count'] = array( 'name' => 'anonymous_count',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                                  'method' => 'fetchAnonymousCount' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array() );

$FunctionList['logged_in_list'] = array( 'name' => 'logged_in_list',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                                 'method' => 'fetchLoggedInList' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( array( 'name' => 'sort_by',
                                                                       'type' => 'mixed',
                                                                       'required' => false ),
                                                                array( 'name' => 'offset',
                                                                       'type' => 'integer',
                                                                       'required' => false ),
                                                                array( 'name' => 'limit',
                                                                       'type' => 'integer',
                                                                       'required' => false ) ) );

$FunctionList['logged_in_users'] = array( 'name' => 'logged_in_users',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                                  'method' => 'fetchLoggedInUsers' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array( array( 'name' => 'sort_by',
                                                                        'type' => 'mixed',
                                                                        'required' => false ),
                                                                 array( 'name' => 'offset',
                                                                        'type' => 'integer',
                                                                        'required' => false ),
                                                                 array( 'name' => 'limit',
                                                                        'type' => 'integer',
                                                                        'required' => false ) ) );
$FunctionList['user_role'] = array( 'name' => 'user_role',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                            'method' => 'fetchUserRole' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'user_id',
                                                                  'type' => 'integer',
                                                                  'required' => true ) ) );


$FunctionList['member_of'] = array( 'name' => 'member_of',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                            'method' => 'fetchMemberOf' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'id',
                                                                  'type' => 'integer',
                                                                  'required' => true ) ) );

$FunctionList['has_access_to'] = array( 'name' => 'has_access_to',
                                        'operation_types' => array(),
                                        'call_method' => array( 'class' => 'eZUserFunctionCollection',
                                                                'method' => 'hasAccessTo' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'module',
                                                                      'type' => 'string',
                                                                      'required' => true ),
                                                               array( 'name' => 'function',
                                                                      'type' => 'string',
                                                                      'required' => true ),
                                                               array( 'name' => 'user_id',
                                                                      'type' => 'integer',
                                                                      'required' => false ) ) );

?>
