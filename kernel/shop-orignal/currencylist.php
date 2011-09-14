<?php
//
// Created on: <08-Nov-2005 13:06:15 dl>
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

$error = false;

if ( $module->hasActionParameter( 'Offset' ) )
{
    $offset = $module->actionParameter( 'Offset' );
}

if ( $module->isCurrentAction( 'NewCurrency' ) )
{
    $module->redirectTo( $module->functionURI( 'editcurrency' ) );
}
else if ( $module->isCurrentAction( 'RemoveCurrency' ) )
{
    $currencyList = $module->hasActionParameter( 'DeleteCurrencyList' ) ? $module->actionParameter( 'DeleteCurrencyList' ) : array();

    eZShopFunctions::removeCurrency( $currencyList );

    eZContentCacheManager::clearAllContentCache();
}
else if ( $module->isCurrentAction( 'ApplyChanges' ) )
{
    $updateDataList = $module->hasActionParameter( 'CurrencyList' ) ? $module->actionParameter( 'CurrencyList' ) : array();

    $currencyList = eZCurrencyData::fetchList();
    $db = eZDB::instance();
    $db->begin();
    foreach ( $currencyList as $currency )
    {
        $currencyCode = $currency->attribute( 'code' );
        if ( isset( $updateDataList[$currencyCode] ) )
        {
            $updateData = $updateDataList[$currencyCode];

            if ( isset( $updateData['status'] ) )
                $currency->setStatus( $updateData['status'] );

            if ( is_numeric( $updateData['custom_rate_value'] ) )
                $currency->setAttribute( 'custom_rate_value', $updateData['custom_rate_value'] );
            else if ( $updateData['custom_rate_value'] == '' )
                $currency->setAttribute( 'custom_rate_value', 0 );

            if ( is_numeric( $updateData['rate_factor'] ) )
                $currency->setAttribute( 'rate_factor', $updateData['rate_factor'] );
            else if ( $updateData['rate_factor'] == '' )
                $currency->setAttribute( 'rate_factor', 0 );

            $currency->sync();
        }
    }
    $db->commit();

    $error = array( 'code' => 0,
                    'description' => ezpI18n::tr( 'kernel/shop', 'Changes were stored successfully.' ) );
}
else if ( $module->isCurrentAction( 'UpdateAutoprices' ) )
{
    $error = eZShopFunctions::updateAutoprices();

    eZContentCacheManager::clearAllContentCache();
}
else if ( $module->isCurrentAction( 'UpdateAutoRates' ) )
{
    $error = eZShopFunctions::updateAutoRates();
}

if ( $error !== false )
{
    if ( $error['code'] != 0 )
        $error['style'] = 'message-error';
    else
        $error['style'] = 'message-feedback';
}

switch ( eZPreferences::value( 'currencies_list_limit' ) )
{
    case '2': { $limit = 25; } break;
    case '3': { $limit = 50; } break;
    default:  { $limit = 10; } break;
}

// fetch currencies
$currencyList = eZCurrencyData::fetchList( null, true, $offset, $limit );
$currencyCount = eZCurrencyData::fetchListCount();

$viewParameters = array( 'offset' => $offset );

$tpl = eZTemplate::factory();

$tpl->setVariable( 'currency_list', $currencyList );
$tpl->setVariable( 'currency_list_count', $currencyCount );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'show_error_message', $error !== false );
$tpl->setVariable( 'error', $error );

$Result = array();
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/shop', 'Available currency list' ),
                                'url' => false ) );
$Result['content'] = $tpl->fetch( "design:shop/currencylist.tpl" );



?>
