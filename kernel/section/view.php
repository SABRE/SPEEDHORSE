<?php
//
// Created on: <31-Sep-2004 16:31:33 bh>
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



$http = eZHTTPTool::instance();
$SectionID = $Params["SectionID"];
$Module = $Params['Module'];
$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$section = eZSection::fetch( $SectionID );

if ( !$section )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$tpl = eZTemplate::factory();

$tpl->setVariable( "view_parameters", $viewParameters );
$tpl->setVariable( "section", $section );

$Result = array();
$Result['content'] = $tpl->fetch( "design:section/view.tpl" );
$Result['path'] = array( array( 'url' => 'section/list',
                                'text' => ezpI18n::tr( 'kernel/section', 'Sections' ) ),
                         array( 'url' => false,
                                'text' => $section->attribute('name') ) );

?>
