<?php
/**
 * File containing the saturation tool handler
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 1.2.0
 * @package kernel
 * @todo Check if this is actually used/implemented
 */
$prepare_action = new eZIEImagePreAction();

$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "saturation" ) )
{
    $saturation = $http->variable("saturation");
}

eZIEImageToolSaturation::toolSaturation(
    $prepare_action->getImagePath(),
    $prepare_action->getNewImagePath(),
    $saturation
);

eZIEImageToolResize::doThumb(
    $prepare_action->getNewImagePath(),
    $prepare_action->getNewThumbnailPath()
);

echo (string)$prepare_action;
eZExecution::cleanExit();
?>
