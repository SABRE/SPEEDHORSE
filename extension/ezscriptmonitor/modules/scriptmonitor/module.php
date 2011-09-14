<?php
/**
 * File containing the module definition.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

$Module = array( 'name' => 'Script monitor' );

$ViewList = array();

$ViewList['list'] = array(
    'script' => 'list.php',
    'default_navigation_part' => 'ezsetupnavigationpart'
    );

$ViewList['view'] = array(
    'script' => 'view.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'params' => array( 'ScriptID' )
    );

?>
