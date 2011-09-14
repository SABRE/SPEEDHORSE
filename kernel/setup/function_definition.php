<?php
//
// Created on: <02-Nov-2004 13:23:10 dl>
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

$FunctionList['version'] = array( 'name' => 'version',
                                  'operation_types' => array( 'read' ),
                                  'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                          'method' => 'fetchFullVersionString' ),
                                  'parameter_type' => 'standard',
                                  'parameters' => array( ) );
$FunctionList['major_version'] = array( 'name' => 'major_version',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                                'method' => 'fetchMajorVersion' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( ) );
$FunctionList['minor_version'] = array( 'name' => 'minor_version',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                                'method' => 'fetchMinorVersion' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( ) );
$FunctionList['release'] = array( 'name' => 'release',
                                  'operation_types' => array( 'read' ),
                                  'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                          'method' => 'fetchRelease' ),
                                  'parameter_type' => 'standard',
                                  'parameters' => array( ) );
$FunctionList['state'] = array( 'name' => 'state',
                                'operation_types' => array( 'read' ),
                                'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                        'method' => 'fetchState' ),
                                'parameter_type' => 'standard',
                                'parameters' => array( ) );
$FunctionList['is_development'] = array( 'name' => 'is_development',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                                 'method' => 'fetchIsDevelopment' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( ) );
$FunctionList['database_version'] = array( 'name' => 'database_version',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                                   'method' => 'fetchDatabaseVersion' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( array( 'name' => 'with_release',
                                                                         'type' => 'bool',
                                                                         'required' => false,
                                                                         'default' => true ) ) );
$FunctionList['database_release'] = array( 'name' => 'database_release',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'eZSetupFunctionCollection',
                                                                   'method' => 'fetchDatabaseRelease' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( ) );
?>
