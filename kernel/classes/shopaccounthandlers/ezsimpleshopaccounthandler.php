<?php
//
// Definition of eZSimpleShopAccountHandler class
//
// Created on: <13-Feb-2003 09:54:35 bf>
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

class eZSimpleShopAccountHandler
{
    function eZSimpleShopAccountHandler()
    {

    }

    /*!
     Will verify that the user has supplied the correct user information.
     Returns true if we have all the information needed about the user.
    */
    function verifyAccountInformation()
    {
        return false;
    }

    /*!
     Redirectes to the user registration page.
    */
    function fetchAccountInformation( &$module )
    {
        $module->redirectTo( '/shop/register/' );
    }

    /*!
     \return custom email for the given order
    */
    function email( $order )
    {
        $email = false;
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );
            $emailNode = $dom->getElementsByTagName( 'email' )->item( 0 );
            if ( $emailNode )
            {
                $email = $emailNode->textContent;
            }
        }

        return $email;
    }

    /*!
     \return custom name for the given order
    */
    function accountName( $order )
    {
        $accountName = '';
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );
            $firstNameNode = $dom->getElementsByTagName( 'first-name' )->item( 0 );
            $lastNameNode = $dom->getElementsByTagName( 'last-name' )->item( 0 );
            $accountName = $firstNameNode->textContent . ' ' . $lastNameNode->textContent;
        }

        return $accountName;
    }

    /*!
     \return the account information for the given order
    */
    function accountInformation( $order )
    {
        $firstName = '';
        $lastName = '';
        $email = '';
        $address = '';

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );

            $firstNameNode = $dom->getElementsByTagName( 'first-name' )->item( 0 );
            if ( $firstNameNode )
            {
                $firstName = $firstNameNode->textContent;
            }

            $lastNameNode = $dom->getElementsByTagName( 'last-name' )->item( 0 );
            if ( $lastNameNode )
            {
                $lastName = $lastNameNode->textContent;
            }

            $emailNode = $dom->getElementsByTagName( 'email' )->item( 0 );
            if ( $emailNode )
            {
                $email = $emailNode->textContent;
            }

            $addressNode = $dom->getElementsByTagName( 'address' )->item( 0 );
            if ( $addressNode )
            {
                $address = $addressNode->textContent;
            }
        }

        return array( 'first_name' => $firstName,
                      'last_name' => $lastName,
                      'email' => $email,
                      'address' => $address
                      );
    }
}

?>
