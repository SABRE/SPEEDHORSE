<?php
/**
 * File containing the eZ Publish module definition.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 1.0.0
 * @package ezmultiupload
 */

$Module = array( 'name' => 'eZ Multiupload', 'variable_params' => true );

$ViewList = array();
$ViewList['upload'] = array( 'script' => 'upload.php',
                             'single_post_actions' => array( 'UploadButton' => 'Upload' ),
                             'params' => array( 'ParentNodeID' ) );

?>
