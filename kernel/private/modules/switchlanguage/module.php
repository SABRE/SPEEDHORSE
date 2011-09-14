<?php
/**
 * File containing the switchlanguage module definition
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

$Module = array( "name" => "SwitchLanguage",
                 "var_params" => false );

$ViewList = array();
$ViewList['to'] = array(
    "script" => "to.php",
    "params" => array( "SiteAccess" ),
    );

$FunctionList = array();

?>
