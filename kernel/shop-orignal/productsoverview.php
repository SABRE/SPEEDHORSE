<?php
//
// Created on: <04-Nov-2005 12:26:52 dl>
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


$module = $Params['Module'];
$offset = $Params['Offset'];
$productClassIdentifier = $Params['ProductClass'];
$productClass = false;
$priceAttributeIdentifier = false;

if ( $module->isCurrentAction( 'Sort' ) )
{
    $productClassIdentifier = $module->hasActionParameter( 'ProductClass' ) ? $module->actionParameter( 'ProductClass' ) : false;
    $sortingField = $module->hasActionParameter( 'SortingField' ) ? $module->actionParameter( 'SortingField' ) : 'none';
    $sortingOrder = $module->hasActionParameter( 'SortingOrder' ) ? $module->actionParameter( 'SortingOrder' ) : 'asc';

    eZPreferences::setValue( 'productsoverview_sorting_field', $sortingField );
    eZPreferences::setValue( 'productsoverview_sorting_order', $sortingOrder );
}

if ( $module->isCurrentAction( 'ShowProducts' ) )
    $productClassIdentifier = $module->hasActionParameter( 'ProductClass' ) ? $module->actionParameter( 'ProductClass' ) : false;

$productClassList = eZShopFunctions::productClassList();

// find selected product class
if ( count( $productClassList ) > 0 )
{
    if ( $productClassIdentifier )
    {
        foreach( $productClassList as $productClassItem )
        {
            if ( $productClassItem->attribute( 'identifier' ) === $productClassIdentifier )
            {
                $productClass = $productClassItem;
                break;
            }
        }
    }
    else
    {
        // use first element of $productClassList
        $productClass = $productClassList[0];
    }
}

if ( is_object( $productClass ) )
    $priceAttributeIdentifier = eZShopFunctions::priceAttributeIdentifier( $productClass );

switch ( eZPreferences::value( 'productsoverview_list_limit' ) )
{
    case '2': { $limit = 25; } break;
    case '3': { $limit = 50; } break;
    default:  { $limit = 10; } break;
}

$sortingField = eZPreferences::value( 'productsoverview_sorting_field' );
$sortingOrder = eZPreferences::value( 'productsoverview_sorting_order' );

$viewParameters = array( 'offset' => $offset );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'product_class_list', $productClassList );
$tpl->setVariable( 'product_class', $productClass );
$tpl->setVariable( 'price_attribute_identifier', $priceAttributeIdentifier );
$tpl->setVariable( 'sorting_field', $sortingField );
$tpl->setVariable( 'sorting_order', $sortingOrder );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/productsoverview.tpl" );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/shop', 'Products overview' ),
                                'url' => false ) );

?>
