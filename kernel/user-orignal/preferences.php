<?php
//
// Created on: <11-Aug-2003 13:10:28 bf>
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

if ( $http->hasPostVariable( 'Function' ) )
    $function = $http->postVariable( 'Function' );
else
    $function = $Params['Function'];

if ( $http->hasPostVariable( 'Key' ) )
    $key = $http->postVariable( 'Key' );
else
    $key = $Params['Key'];


if ( $http->hasPostVariable( 'Value' ) )
    $value = $http->postVariable( 'Value' );
else
    $value = $Params['Value'];

// Set user preferences
if ( eZOperationHandler::operationIsAvailable( 'user_preferences' ) )
{
    $operationResult = eZOperationHandler::execute( 'user',
                                                    'preferences', array( 'key'    => $key,
                                                                          'value'  => $value ) );
}
else
{
    eZPreferences::setValue( $key, $value );
}

// For use by ajax calls
if ( $function === 'set_and_exit' )
{
    eZDB::checkTransactionCounter();
    eZExecution::cleanExit();
}

if ( $http->hasPostVariable( 'RedirectURIAfterSet' ) )
{
    $url = $http->postVariable( 'RedirectURIAfterSet' );
}
else
{
    // Extract URL to redirect to from user parameters.
    $urlArray = array_splice( $Params['Parameters'], 3 );
    foreach ( $urlArray as $key => $val ) // remove all the array elements that don't seem like URL parts
    {
        if ( !is_numeric( $key ) )
            unset( $urlArray[$key] );
    }
    $url = implode( '/', $urlArray );
    unset( $urlArray );
}

if ( $url )
{
    foreach ( array_keys( $Params['UserParameters'] ) as $key )
    {
        if ( $key == 'offset' )
            continue;
        $url .= '/(' . $key . ')/' . $Params['UserParameters'][$key];
    }
    $module->redirectTo( '/'.$url );
}
else if ( isset( $_SERVER['HTTP_REFERER'] ) )
{
    $preferredRedirectionURI = eZURI::decodeURL( $_SERVER['HTTP_REFERER'] );

    // We should exclude OFFSET from $preferredRedirectionURI
    $exploded = explode( '/', $preferredRedirectionURI );
    foreach ( array_keys( $exploded ) as $itemKey )
    {
        $item = $exploded[$itemKey];
        if ( $item == '(offset)' )
        {
            array_splice( $exploded, $itemKey, 2 );
            break;
        }
    }
    $redirectURI = implode( '/', $exploded );

    // Protect against redirect loop
    if ( strpos( $redirectURI, '/user/preferences/set'  ) !== false )
        $module->redirectTo( '/' );
    else
        eZRedirectManager::redirectTo( $module, /* $default = */ false, /* $view = */ true, /* $disallowed = */ false, $redirectURI );
    return;
}
else
{
    $module->redirectTo( $http->postVariable( 'RedirectURI', $http->sessionVariable( 'LastAccessesURI', '/' ) ) );
}

?>
