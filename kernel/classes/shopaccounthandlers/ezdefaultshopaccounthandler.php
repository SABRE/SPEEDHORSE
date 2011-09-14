<?php
//
// Definition of eZDefaultShopAccountHandler class
//
// Created on: <13-Feb-2003 08:58:14 bf>
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

class eZDefaultShopAccountHandler
{
    function eZDefaultShopAccountHandler()
    {

    }

    /*!
     Will verify that the user has supplied the correct user information.
     Returns true if we have all the information needed about the user.
    */
    function verifyAccountInformation()
    {
        // Check login
        $user = eZUser::currentUser();
        if ( !$user->isLoggedIn() )
            return false;
        else
            return true;
    }

    /*!
     Redirectes to the user registration page.
    */
    function email( $order = false )
    {
        if ( $order === false )
            $user = eZUser::currentUser();
        else
            $user = $order->attribute( 'user' );

        if ( is_object( $user ) )
            return $user->attribute( 'email' );
        else
            return null;
    }

    /*!
     \return the custom name for the given order
    */
    function accountName( $order = false )
    {
        if ( $order === false )
            $user = eZUser::currentUser();
        else
            $user = $order->attribute( 'user' );

        if ( is_object( $user ) )
        {
            $userObject = $user->attribute( 'contentobject' );
            $accountName = $userObject->attribute( 'name' );
        }
        else
            $accountName = null;
        return $accountName;
    }

    function fetchAccountInformation( &$module )
    {
        $http = eZHTTPTool::instance();
        $http->setSessionVariable( 'RedirectAfterLogin', '/shop/basket/' );
        $http->setSessionVariable( 'DoCheckoutAutomatically', true );
        $module->redirectTo( '/user/login/' );
    }

    function accountInformation( $order )
    {
        $user = $order->user();
        $userObject = $user->attribute( "contentobject" );
        $dataMap = $userObject->dataMap();

        return array( 'first_name' => $dataMap['first_name']->content(),
                      'last_name' => $dataMap['last_name']->content(),
                      'email' => $user->attribute( "email" ) );
    }
}

?>
