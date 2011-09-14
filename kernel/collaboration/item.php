<?php
//
// Created on: <23-Jan-2003 11:37:30 amos>
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
$ViewMode = $Params['ViewMode'];
$ItemID = $Params['ItemID'];

$Offset = $Params['Offset'];
if ( !is_numeric( $Offset ) )
    $Offset = 0;

$collabItem = eZCollaborationItem::fetch( $ItemID );
if ( $collabItem === null )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

$collabHandler = $collabItem->handler();
$collabItem->handleView( $ViewMode );
$template = $collabHandler->template( $ViewMode );
$collabTitle = $collabItem->title();

$viewParameters = array( 'offset' => $Offset );

$tpl = eZTemplate::factory();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'collab_item', $collabItem );

$Result = array();
$Result['content'] = $tpl->fetch( $template );

$collabHandler->readItem( $collabItem );

$Result['path'] = array( array( 'url' => 'collaboration/view/summary',
                                'text' => ezpI18n::tr( 'kernel/collaboration', 'Collaboration' ) ),
                         array( 'url' => false,
                                'text' => $collabTitle ) );

?>