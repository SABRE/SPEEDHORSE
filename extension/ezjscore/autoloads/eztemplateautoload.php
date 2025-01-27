<?php
/**
 * Template autoload definition for eZ JS Core
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

/**
 * Look in the operator files for documentation on use and parameters definition.
 *
 * @var array $eZTemplateOperatorArray
 */

$eZTemplateOperatorArray = array();
$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezjscore/autoloads/ezjscpackertemplatefunctions.php',
                                    'class' => 'ezjscPackerTemplateFunctions',
                                    'operator_names' => array( 'ezscript',
                                                               'ezscript_require',
                                                               'ezscript_load',
                                                               'ezscriptfiles',
                                                               'ezcss',
                                                               'ezcss_require',
                                                               'ezcss_load',
                                                               'ezcssfiles' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezjscore/autoloads/ezjscencodingtemplatefunctions.php',
                                    'class' => 'ezjscEncodingTemplateFunctions',
                                    'operator_names' => array( 'json_encode',
                                                               'xml_encode',
                                                               'node_encode',
) );

$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezjscore/autoloads/ezjscaccesstemplatefunctions.php',
                                    'class' => 'ezjscAccessTemplateFunctions',
                                    'operator_names' => array( 'has_access_to_limitation',
) );


?>
