<?php
//
// Created on: <31-Jul-2002 16:47:15 bf>
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

$Module = array( "name" => "eZShop",
                 "variable_params" => true );

$ViewList = array();
$ViewList["add"] = array(
    "functions" => array( 'buy' ),
    "script" => "add.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( "ObjectID", "Quantity" ) );

$ViewList["orderview"] = array(
    "functions" => array( 'buy' ),
    "script" => "orderview.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( "OrderID" ) );

$ViewList['updatebasket'] = array(
    'functions' => array( 'buy' ),
    'script' => 'updatebasket.php',
    'default_navigation_part' => 'ezshopnavigationpart',
    'params' => array(  ) );

$ViewList["basket"] = array(
    "functions" => array( 'buy' ),
    "script" => "basket.php",
    "default_navigation_part" => 'ezmynavigationpart',
    'unordered_params' => array( 'error' => 'Error' ),
    "params" => array(  ) );

$ViewList["register"] = array(
    "functions" => array( 'buy' ),
    "script" => "register.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'CancelButton' => 'Cancel'
                                    ),
    "params" => array(  ) );

$ViewList["userregister"] = array(
    "functions" => array( 'buy' ),
    "script" => "userregister.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'CancelButton' => 'Cancel'
                                    )
    );

$ViewList["wishlist"] = array(
    "functions" => array( 'buy' ),
    "script" => "wishlist.php",
    "default_navigation_part" => 'ezmynavigationpart',
    'unordered_params' => array( 'offset' => 'Offset' ),
    "params" => array(  ) );

$ViewList["orderlist"] = array(
    "functions" => array( 'administrate' ),
    "script" => "orderlist.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "unordered_params" => array( "offset" => "Offset" ),
    "params" => array(  ) );

$ViewList["archivelist"] = array(
    "functions" => array( 'administrate' ),
    "script" => "archivelist.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "unordered_params" => array( "offset" => "Offset" ),
    "params" => array(  ) );

$ViewList["removeorder"] = array(
    "functions" => array( 'administrate' ),
    "script" => "removeorder.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );

$ViewList["archiveorder"] = array(
    "functions" => array( 'administrate' ),
    "script" => "archiveorder.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );

$ViewList["unarchiveorder"] = array(
    "functions" => array( 'administrate' ),
    "script" => "unarchiveorder.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );


$ViewList["customerlist"] = array(
    "functions" => array( 'administrate' ),
    "script" => "customerlist.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "unordered_params" => array( 'offset' => 'Offset' ),
    "params" => array(  ) );

$ViewList["customerorderview"] = array(
    "functions" => array( 'administrate' ),
    "script" => "customerorderview.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( "CustomerID", "Email" ) );

$ViewList["statistics"] = array(
    "functions" => array( 'administrate' ),
    "script" => "orderstatistics.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( 'Year', 'Month' ) );

$ViewList["confirmorder"] = array(
    "functions" => array( 'buy' ),
    "script" => "confirmorder.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array(  ) );

$ViewList["checkout"] = array(
    "functions" => array( 'buy' ),
    "script" => "checkout.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array(  ) );

$ViewList["vattype"] = array(
    "functions" => array( 'setup' ),
    "script" => "vattype.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    'single_post_actions' => array( 'RemoveVatTypeButton'  => 'Remove',
                                    'AddVatTypeButton'     => 'Add',
                                    'SaveVatTypeButton'    => 'SaveChanges',
                                    'ConfirmRemovalButton' => 'ConfirmRemoval' ),
    'post_action_parameters' => array( 'Remove'         => array( 'vatTypeIDList' => 'vatTypeIDList' ),
                                       'ConfirmRemoval' => array( 'vatTypeIDList' => 'vatTypeIDList',
                                                                  'VatReplacement' => 'VatReplacement' ) ),
    "params" => array(  ) );

$ViewList["vatrules"] = array(
    "functions" => array( 'setup' ),
    "script" => "vatrules.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );

$ViewList["editvatrule"] = array(
    "functions" => array( 'setup' ),
    "script" => "editvatrule.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    'single_post_actions' => array( 'CancelButton' => 'Cancel',
                                    'CreateButton' => 'Create',
                                    'StoreChangesButton' => 'StoreChanges' ),
    'post_action_parameters' => array( 'Create' => array( 'Country' => 'Country',
                                                          'Categories' => 'Categories',
                                                          'VatType' => 'VatType' ),
                                       'StoreChanges' => array( 'RuleID' => 'RuleID',
                                                                'Country' => 'Country',
                                                                'Categories' => 'Categories',
                                                                'VatType' => 'VatType' ) ),
    'params' => array( 'ruleID' ),
    'unordered_params' => array( 'currency' => 'Currency' ) );


$ViewList["productcategories"] = array(
    "functions" => array( 'setup' ),
    "script" => "productcategories.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    'single_post_actions' => array( 'AddCategoryButton'    => 'Add',
                                    'RemoveCategoryButton' => 'Remove',
                                    'ConfirmRemovalButton' => 'ConfirmRemoval', // remove dialog
                                    'CancelRemovalButton'  => 'CancelRemoval',  // remove dialog
                                    'SaveCategoriesButton' => 'StoreChanges' ),
    'post_action_parameters' => array( 'Remove'         => array( 'CategoryIDList' => 'CategoryIDList' ),
                                       'ConfirmRemoval' => array( 'CategoryIDList' => 'CategoryIDList' ) ),
    "params" => array(  ) );

$ViewList["discountgroup"] = array(
    "functions" => array( 'setup' ),
    "script" => "discountgroup.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array(  ) );

$ViewList["discountgroupedit"] = array(
    "functions" => array( 'setup' ),
    "script" => "discountgroupedit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( 'DiscountGroupID' ) );

$ViewList["discountruleedit"] = array(
    "functions" => array( 'setup' ),
    "script" => "discountruleedit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    "params" => array( 'DiscountGroupID', 'DiscountRuleID'  ) );

$ViewList["discountgroupview"] = array(
    'functions' => array( 'setup' ),
    "script" => "discountgroupmembershipview.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    "params" => array( 'DiscountGroupID' ) );

$ViewList['status'] = array(
    "functions" => array( 'edit_status' ),
    "script" => 'status.php',
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array(  ) );

$ViewList['setstatus'] = array(
    "functions" => array( 'setstatus' ),
    "script" => 'setstatus.php',
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array(  ) );

$ViewList['currencylist'] = array(
    'functions' => array( 'setup' ),
    'script' => 'currencylist.php',
    'default_navigation_part' => 'ezshopnavigationpart',
    'unordered_params' => array( 'offset' => 'Offset' ),
    'single_post_actions' => array( 'NewCurrencyButton' => 'NewCurrency',
                                    'RemoveCurrencyButton' => 'RemoveCurrency',
                                    'ApplyChangesButton' => 'ApplyChanges',
                                    'UpdateAutopricesButton' => 'UpdateAutoprices',
                                    'UpdateAutoRatesButton' => 'UpdateAutoRates' ),
    'post_action_parameters' => array( 'RemoveCurrency' => array( 'DeleteCurrencyList' => 'DeleteCurrencyList' ),
                                       'ApplyChanges' => array( 'CurrencyList' => 'CurrencyList',
                                                                'Offset' => 'Offset' ),
                                       'UpdateAutoprices' => array( 'Offset' => 'Offset' ),
                                       'UpdateAutoRates' => array( 'Offset' => 'Offset' ) ),
    'params' => array(  ) );

$ViewList['editcurrency'] = array(
    'functions' => array( 'setup' ),
    'script' => 'editcurrency.php',
    'default_navigation_part' => 'ezshopnavigationpart',
    'single_post_actions' => array( 'CancelButton' => 'Cancel',
                                    'CreateButton' => 'Create',
                                    'StoreChangesButton' => 'StoreChanges' ),
    'post_action_parameters' => array( 'Create' => array( 'CurrencyData' => 'CurrencyData' ),
                                       'StoreChanges' => array( 'CurrencyData' => 'CurrencyData',
                                                                'OriginalCurrencyCode' => 'OriginalCurrencyCode' ) ),
    'params' => array(),
    'unordered_params' => array( 'currency' => 'Currency' ) );

$ViewList['preferredcurrency'] = array(
    'functions' => array( 'buy' ),
    'script' => 'preferredcurrency.php',
    'default_navigation_part' => 'ezshopnavigationpart',
    'params' => array(  ) );

$ViewList['productsoverview'] = array(
    'functions' => array( 'administrate' ),
    'script' => 'productsoverview.php',
    'default_navigation_part' => 'ezshopnavigationpart',
    'single_post_actions' => array( 'ShowProductsButton' => 'ShowProducts',
                                    'SortButton' => 'Sort' ),
    'post_action_parameters' => array( 'ShowProducts' => array( 'ProductClass' => 'ProductClass' ),
                                       'Sort' => array( 'ProductClass' => 'ProductClass',
                                                        'SortingField' => 'SortingField',
                                                        'SortingOrder' => 'SortingOrder' ) ),
    'params' => array(),
    'unordered_params' => array( 'product_class' => 'ProductClass',
                                 'offset' => 'Offset' ) );

$ViewList['setpreferredcurrency'] = array(
    'functions' => array( 'buy' ),
    'script' => 'setpreferredcurrency.php',
    'default_navigation_part' => 'ezshopnavigationpart',
    'single_post_actions' => array( 'SetButton' => 'Set' ),
    'post_action_parameters' => array( 'Set' => array( 'Currency' => 'Currency' ) ),
    'unordered_params' => array( 'currency' => 'Currency' ),
    'params' => array(  ) );

$ViewList['setusercountry'] = array(
    'functions' => array( 'buy' ),
    'script' => 'setusercountry.php',
    'default_navigation_part' => 'ezshopnavigationpart',
    'single_post_actions' => array( 'ApplyButton' => 'Set' ),
    'post_action_parameters' => array( 'Set' => array( 'Country' => 'Country' ) ),
    'unordered_params' => array( 'country' => 'Country' ),
    'params' => array(  ) );


$FromStatus = array(
    'name' => 'FromStatus',
    'values' => array(),
    'path' => 'classes/',
    'file' => 'ezorderstatus.php',
    'class' => 'eZOrderStatus',
    'function' => 'fetchPolicyList',
    'parameter' => array( false ) );

$ToStatus = array(
    'name' => 'ToStatus',
    'values' => array(),
    'path' => 'classes/',
    'file' => 'ezorderstatus.php',
    'class' => 'eZOrderStatus',
    'function' => 'fetchPolicyList',
    'parameter' => array( false ) );

$FunctionList = array();
$FunctionList['setup'] = array( );
$FunctionList['administrate'] = array( );
$FunctionList['buy'] = array( );
$FunctionList['edit_status'] = array( );
$FunctionList['setstatus'] = array( 'FromStatus' => $FromStatus,
                                    'ToStatus' => $ToStatus );

?>
