<?php
//
// Definition of Settings class
//
// Created on: <14-May-2003 16:30:26 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
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

/*! \file
*/


$http = eZHTTPTool::instance();

$Module = $Params['Module'];

$user = eZUser::currentUser();

$availableHandlers = eZNotificationEventFilter::availableHandlers();


$db = eZDB::instance();
$db->begin();
if ( $http->hasPostVariable( 'Store' ) )
{
    foreach ( $availableHandlers as $handler )
    {
        $handler->storeSettings( $http, $Module );
    }

}

foreach ( $availableHandlers as $handler )
{
    $handler->fetchHttpInput( $http, $Module );
}
$db->commit();

$viewParameters = array( 'offset' => $Params['Offset'] );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'user', $user );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:notification/settings.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/notification', 'Notification settings' ) ) );


?>
