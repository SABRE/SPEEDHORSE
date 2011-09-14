<?php
/**
 * File containing timeout controller
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */
// This will time out


while ( true )
{
    $variable = 'text' + 42;
}

$tpl = eZTemplate::factory();

$Result = array();
$Result['content'] = $tpl->fetch( 'design:test/timeout.tpl' );
return $Result;

?>
