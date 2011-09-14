<?php
//
// Definition of Customlist class
//
// Created on: <01-Mar-2004 13:06:15 wy>
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



$module = $Params["Module"];

$offset = $Params['Offset'];
$limit = 15;

$tpl = eZTemplate::factory();

$http = eZHTTPTool::instance();

$customerArray = eZOrder::customerList( $offset, $limit );

$customerCount = eZOrder::customerCount();

$tpl->setVariable( "customer_list", $customerArray );
$tpl->setVariable( "customer_list_count", $customerCount );
$tpl->setVariable( "limit", $limit );

$viewParameters = array( 'offset' => $offset );
$tpl->setVariable( "module", $module );
$tpl->setVariable( 'view_parameters', $viewParameters );

$path = array();
$path[] = array( 'text' => ezpI18n::tr( 'kernel/shop', 'Customer list' ),
                 'url' => false );

$Result = array();
$Result['path'] = $path;

$Result['content'] = $tpl->fetch( "design:shop/customerlist.tpl" );

?>
