<?php
/**
 * Template autoload definition for eZ Online Editor
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezoe/autoloads/ezoetemplateutils.php',
                                    'class' => 'eZOETemplateUtils',
                                    'operator_names' => array( 'ezoe_ini_section' ) );


?>
