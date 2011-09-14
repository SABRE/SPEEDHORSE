<?php
//
// Created on: <2-May-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor extension for eZ Publish
// SOFTWARE RELEASE: 4.5.0
// COPYRIGHT NOTICE: Copyright (C) 1999-2011 eZ Systems AS
// SOFTWARE LICENSE: eZ Proprietary Use License v1.0
// NOTICE: >
//   This source file is part of the eZ Publish (tm) CMS and is
//   licensed under the terms and conditions of the eZ Proprietary
//   Use License v1.0 (eZPUL).
// 
//   A copy of the eZPUL was included with the software. If the
//   license is missing, request a copy of the license via email
//   at eZPUL-v1.0@ez.no or via postal mail at
//     Attn: Licensing Dept. eZ Systems AS, Klostergata 30, N-3732 Skien, Norway
// 
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

// General popup dialog
// used for things like help and merge cells dialogs

$objectID      = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : 0;
$objectVersion = isset( $Params['ObjectVersion'] ) ? (int) $Params['ObjectVersion'] : 0;
$dialog        = isset( $Params['Dialog'] ) ? trim( $Params['Dialog'] ) : '';

if ( $objectID === 0  || $objectVersion === 0 )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'ObjectID/ObjectVersion' ) );
   eZExecution::cleanExit();
}

$object = eZContentObject::fetch( $objectID );

if ( !$object )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ObjectId', '%value' => $objectID ) );
   eZExecution::cleanExit();
}


if ( $dialog === '' )
{
   echo ezpI18n::tr( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'Dialog' ) );
   eZExecution::cleanExit();
}





$ezoeInfo = ezoeInfo::info();

$tpl = eZTemplate::factory();
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'object_id', $objectID );
$tpl->setVariable( 'object_version', $objectVersion );

$tpl->setVariable( 'ezoe_name', $ezoeInfo['Name'] );
$tpl->setVariable( 'ezoe_version', $ezoeInfo['Version'] );
$tpl->setVariable( 'ezoe_copyright', $ezoeInfo['Copyright'] );
$tpl->setVariable( 'ezoe_license', $ezoeInfo['License'] );

// use persistent_variable like content/view does, sending parameters
// to pagelayout as a hash.
$tpl->setVariable( 'persistent_variable', array() );




// run template and return result
$Result = array();
$Result['content'] = $tpl->fetch( 'design:ezoe/' . $dialog . '.tpl' );
$Result['pagelayout'] = 'design:ezoe/popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );
return $Result;


?>
