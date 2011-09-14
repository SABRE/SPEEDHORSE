<?php
//
// Created on: <03-Oct-2006 13:01:25 sp>
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


$FunctionList['collected_info_count'] = array( 'name' => 'collected_info_count',
                                               'operation_types' => array( 'read' ),
                                               'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                       'method' => 'fetchCollectedInfoCount' ),
                                               'parameter_type' => 'standard',
                                               'parameters' => array( array( 'name' => 'object_attribute_id',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'object_id',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'value',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'creator_id',
                                                                             'type' => 'integer',
                                                                             'required' => false,
                                                                             'default' => false ),
                                                                      array( 'name' => 'user_identifier',
                                                                             'type' => 'string',
                                                                             'required' => false,
                                                                             'default' => false ) ) );

$FunctionList['collected_info_count_list'] = array( 'name' => 'collected_info_count_list',
                                               'operation_types' => array( 'read' ),
                                               'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                       'method' => 'fetchCollectedInfoCountList' ),
                                               'parameter_type' => 'standard',
                                               'parameters' => array( array( 'name' => 'object_attribute_id',
                                                                             'type' => 'integer',
                                                                             'required' => true,
                                                                             'default' => false ) ) );


$FunctionList['collected_info_collection'] = array( 'name' => 'collected_info_collection',
                                                    'operation_types' => array( 'read' ),
                                                    'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                            'method' => 'fetchCollectedInfoCollection' ),
                                                    'parameter_type' => 'standard',
                                                    'parameters' => array( array( 'name' => 'collection_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'contentobject_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ) ) );

$FunctionList['collected_info_list'] = array( 'name' => 'collected_info_list',
                                                    'operation_types' => array( 'read' ),
                                                    'call_method' => array( 'class' => 'eZInfocollectorFunctionCollection',
                                                                            'method' => 'fetchCollectionsList' ),
                                                    'parameter_type' => 'standard',
                                                    'parameters' => array( array( 'name' => 'object_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'creator_id',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'user_identifier',
                                                                                  'type' => 'string',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'limit',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'offset',
                                                                                  'type' => 'integer',
                                                                                  'required' => false,
                                                                                  'default' => false ),
                                                                           array( 'name' => 'sort_by',
                                                                                  'type' => 'array',
                                                                                  'required' => false,
                                                                                  'default' => array() ) ) );


?>
