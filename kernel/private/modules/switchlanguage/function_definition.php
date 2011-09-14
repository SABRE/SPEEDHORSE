<?php
/**
 * File containing function definition for LanguageSwitcher module
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

$FunctionList = array();
$FunctionList['url_alias'] = array( 'name' => 'url_alias',
                                    'call_method' => array( 'class' => 'ezpLanguageSwitcherFunctionCollection',
                                                            'method' => 'fetchUrlAlias' ),
                                    'parameters' => array(
                                                           array( 'name' => 'node_id',
                                                                  'type' => 'integer',
                                                                  'default' => false,
                                                                  'required' => false ),

                                                           array( 'name' => 'path',
                                                                  'type' => 'string',
                                                                  'default' => false,
                                                                  'required' => false ),

                                                           array( 'name' => 'locale',
                                                                  'type' => 'string',
                                                                  'default' => false,
                                                                  'required' => true ), ) );

?>
