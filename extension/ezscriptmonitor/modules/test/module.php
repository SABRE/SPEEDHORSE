<?php
/**
 * File containing module definition
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

$Module = array( 'name' => 'Test',
                 'variable_params' => true );

$ViewList = array();

$ViewList['timeout'] = array(
    'script' => 'timeout.php',
    );

$ViewList['antitimeout'] = array(
    'script' => 'antitimeout.php',
    );

?>
