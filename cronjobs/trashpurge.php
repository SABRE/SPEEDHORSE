<?php
/**
 * Trash purge cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU General Public License v2.0
 * @version 4.5.0
 * @package kernel
 */

$purgeHandler = new eZScriptTrashPurge( eZCLI::instance() );
$purgeHandler->run();

?>
