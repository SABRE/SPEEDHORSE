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
$FunctionList['list'] = array( 'name' => 'list',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'class' => 'eZURLFunctionCollection',
                                                       'method' => 'fetchList' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array( array( 'name' => 'is_valid',
                                                                    'required' => false,
                                                                    'default' => null ),
                                                             array( 'name' => 'offset',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'limit',
                                                                    'required' => false,
                                                                    'default' => false ),
                                                             array( 'name' => 'only_published',
                                                                    'required' => false,
                                                                    'default' => false ) ) );
$FunctionList['list_count'] = array( 'name' => 'list_count',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'class' => 'eZURLFunctionCollection',
                                                       'method' => 'fetchListCount' ),
                                      'parameter_type' => 'standard',
                                      'parameters' => array( array( 'name' => 'is_valid',
                                                                    'required' => false,
                                                                    'default' => null ),
                                                             array( 'name' => 'only_published',
                                                                    'required' => false,
                                                                    'default' => false ) ) );

?>
