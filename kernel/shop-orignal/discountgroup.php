<?php
//
// Definition of  class
//
// Created on: <25-Nov-2002 15:40:10 wy>
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


$module = $Params['Module'];

$http = eZHTTPTool::instance();

$discountGroupArray = eZDiscountRule::fetchList();

if ( $http->hasPostVariable( "AddDiscountGroupButton" ) )
{
    $params = array();
    $Module->redirectTo( $Module->functionURI( "discountgroupedit" ) );
    return;
}

if ( $http->hasPostVariable( "EditGroupButton" ) && $http->hasPostVariable( "EditGroupID" ) )
{
    $Module->redirectTo( $Module->functionURI( "discountgroupedit" ) . "/" . $http->postVariable( "EditGroupID" ) );
    return;
}

if ( $http->hasPostVariable( "RemoveDiscountGroupButton" ) )
{
    $discountRuleIDList = $http->postVariable( "discountGroupIDList" );

    $db = eZDB::instance();
    $db->begin();
    foreach ( $discountRuleIDList  as $discountRuleID )
    {
        eZDiscountRule::removeByID( $discountRuleID );
    }
    $db->commit();

    // we changed prices of products (no discount now) => remove content caches
    eZContentCacheManager::clearAllContentCache();

    $module->redirectTo( $module->functionURI( "discountgroup" ) . "/" );
    return;
}
$module->setTitle( "View discount group" );
$tpl = eZTemplate::factory();
$tpl->setVariable( "discountgroup_array", $discountGroupArray );
$tpl->setVariable( "module", $module );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/discountgroup.tpl" );
$Result['path'] = array( array( 'url' => '/shop/discountgroup/',
                                'text' => ezpI18n::tr( 'kernel/shop', 'Discount group' ) ) );
?>
