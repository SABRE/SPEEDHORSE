<?php
//
// Created on: <23-May-2003 16:45:07 amos>
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

$FunctionList['object'] = array( 'name' => 'object',
                                 'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                         'method' => 'fetchSectionObject' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array( array( 'name' => 'section_id',
                                                               'type' => 'integer',
                                                               'required' => false,
                                                               'default' => false ),
                                                        array( 'name' => 'identifier',
                                                               'type' => 'string',
                                                               'required' => false,
                                                               'default' => false ) ) );

$FunctionList['list'] = array( 'name' => 'list',
                               'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                       'method' => 'fetchSectionList' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( ) );

$FunctionList['object_list'] = array( 'name' => 'object_list',
                                      'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                              'method' => 'fetchObjectList' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array( array( 'name' => 'section_id',
                                                                    'type' => 'integer',
                                                                    'required' => true ),
                                                             array( 'name' => 'offset',
                                                                    'type' => 'integer',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'limit',
                                                                    'type' => 'integer',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'sort_order',
                                                                    'type' => 'variant',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'status',
                                                                    'type' => 'string',
                                                                    'required' => false,
                                                                    'default' => false ) ) );

$FunctionList['object_list_count'] = array( 'name' => 'object_list_count',
                                            'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                                    'method' => 'fetchObjectListCount' ),
                                            'parameter_type' => 'standard',
                                            'parameters' => array( array( 'name' => 'section_id',
                                                                          'type' => 'integer',
                                                                          'required' => true ),
                                                                   array( 'name' => 'status',
                                                                          'type' => 'string',
                                                                          'required' => false,
                                                                          'default' => false ) ) );

$FunctionList['roles'] = array( 'name' => 'roles',
                                'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                        'method' => 'fetchRoles' ),
                                'parameter_type' => 'standard',
                                'parameters' => array( array( 'name' => 'section_id',
                                                              'type' => 'integer',
                                                              'required' => true ) ) );

$FunctionList['user_roles'] = array( 'name' => 'user_roles',
                                     'call_method' => array( 'class' => 'eZSectionFunctionCollection',
                                                             'method' => 'fetchUserRoles' ),
                                     'parameter_type' => 'standard',
                                     'parameters' => array( array( 'name' => 'section_id',
                                                                   'type' => 'integer',
                                                                   'required' => true ) ) );

?>
