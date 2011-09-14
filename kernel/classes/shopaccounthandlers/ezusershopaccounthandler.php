<?php
//
// Definition of eZUserShopAccountHandler class
//
// Created on: <04-Mar-2003 09:40:49 bf>
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

class eZUserShopAccountHandler
{
    function eZUserShopAccountHandler()
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
        $module->redirectTo( '/shop/userregister/' );
    }

    /*!
     \return the account information for the given order
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
     \return the account information for the given order
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

    function accountInformation( $order )
    {
        $firstName = '';
        $lastName = '';
        $email = '';
        $street1 = '';
        $street2 = '';
        $zip = '';
        $place = '';
        $country = '';
        $comment = '';
        $state = '';

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

            $street1Node = $dom->getElementsByTagName( 'street1' )->item( 0 );
            if ( $street1Node )
            {
                $street1 = $street1Node->textContent;
            }

            $street2Node = $dom->getElementsByTagName( 'street2' )->item( 0 );
            if ( $street2Node )
            {
                $street2 = $street2Node->textContent;
            }

            $zipNode = $dom->getElementsByTagName( 'zip' )->item( 0 );
            if ( $zipNode )
            {
                $zip = $zipNode->textContent;
            }

            $placeNode = $dom->getElementsByTagName( 'place' )->item( 0 );
            if ( $placeNode )
            {
                $place = $placeNode->textContent;
            }

            $stateNode = $dom->getElementsByTagName( 'state' )->item( 0 );
            if ( $stateNode )
            {
                $state = $stateNode->textContent;
            }

            $countryNode = $dom->getElementsByTagName( 'country' )->item( 0 );
            if ( $countryNode )
            {
                $country = $countryNode->textContent;
            }

            $commentNode = $dom->getElementsByTagName( 'comment' )->item( 0 );
            if ( $commentNode )
            {
                $comment = $commentNode->textContent;
            }
        }

        return array( 'first_name' => $firstName,
                      'last_name' => $lastName,
                      'email' => $email,
                      'street1' => $street1,
                      'street2' => $street2,
                      'zip' => $zip,
                      'place' => $place,
                      'state' => $state,
                      'country' => $country,
                      'comment' => $comment,
                      );
    }
}

?>
