<?php
/**
 * Trash purge cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package kernel
 */

$purgeHandler = new eZScriptTrashPurge( eZCLI::instance() );
$purgeHandler->run();

?>
