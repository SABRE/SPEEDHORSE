<?php
//
// Created on: <07-Oct-2003 11:15:12 kk>
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



$Module = $Params['Module'];

$http = eZHTTPTool::instance();

$contentIni = eZINI::instance( 'content.ini' );

$Module->setTitle( ezpI18n::tr( 'kernel/setup', 'Setup menu' ) );
$tpl = eZTemplate::factory();

$Result = array();
$Result['content'] = $tpl->fetch( 'design:setup/setupmenu.tpl' );
$Result['path'] = array( array( 'url' => '/setup/menu',
                                'text' => ezpI18n::tr( 'kernel/setup', 'Setup menu' ) ) );

?>
