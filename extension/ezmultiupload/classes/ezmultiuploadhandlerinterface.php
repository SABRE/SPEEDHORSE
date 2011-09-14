<?php
/**
 * File containing the eZMultiuploadHandlerInterface interface.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 1.0.0
 * @package ezmultiupload
 */

interface eZMultiuploadHandlerInterface
{
    static public function preUpload( &$result );
    static public function postUpload( &$result );
}

?>
