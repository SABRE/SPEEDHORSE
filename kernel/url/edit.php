<?php
//
// Created on: <04-Jul-2003 10:30:48 wy>
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
$Module = $Params['Module'];
$urlID = null;
if ( isset( $Params["ID"] ) )
    $urlID = $Params["ID"];

if ( is_numeric( $urlID ) )
{
    $url = eZURL::fetch( $urlID );
    if ( !$url )
    {
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }
}
else
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$http = eZHTTPTool::instance();
if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $Module->redirectToView( 'list' );
    return;
}

if ( $Module->isCurrentAction( 'Store' ) )
{
    if ( $http->hasPostVariable( 'link' ) )
    {
        $link = $http->postVariable( 'link' );
        $url->setAttribute( 'url', $link );
        $url->store();
        eZURLObjectLink::clearCacheForObjectLink( $urlID );
    }
    $Module->redirectToView( 'list' );
    return;
}

$Module->setTitle( "Edit link " . $url->attribute( "id" ) );

// Template handling

$tpl = eZTemplate::factory();

$tpl->setVariable( "Module", $Module );
$tpl->setVariable( "url", $url );

$Result = array();
$Result['content'] = $tpl->fetch( "design:url/edit.tpl" );
$Result['path'] = array( array( 'url' => '/url/edit/',
                                'text' => ezpI18n::tr( 'kernel/url', 'URL edit' ) ) );
?>
