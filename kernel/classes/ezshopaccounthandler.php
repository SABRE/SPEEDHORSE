<?php
//
// Definition of eZShopAccountHandler class
//
// Created on: <12-Feb-2003 16:50:52 bf>
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

class eZShopAccountHandler
{
    function eZShopAccountHandler()
    {

    }

    /**
     * Returns a shared instance of the eZShopAccountHandler class
     * as defined in shopaccount.ini[HandlerSettings]Repositories
     * and ExtensionRepositories.
     *
     * @return eZDefaultShopAccountHandler Or similar clases.
     */
    static function instance()
    {
        $accountHandler = null;
        if ( eZExtension::findExtensionType( array( 'ini-name' => 'shopaccount.ini',
                                                    'repository-group' => 'HandlerSettings',
                                                    'repository-variable' => 'Repositories',
                                                    'extension-group' => 'HandlerSettings',
                                                    'extension-variable' => 'ExtensionRepositories',
                                                    'type-group' => 'AccountSettings',
                                                    'type-variable' => 'Handler',
                                                    'alias-group' => 'AccountSettings',
                                                    'alias-variable' => 'Alias',
                                                    'subdir' => 'shopaccounthandlers',
                                                    'type-directory' => false,
                                                    'extension-subdir' => 'shopaccounthandlers',
                                                    'suffix-name' => 'shopaccounthandler.php' ),
                                             $out ) )
        {
            $filePath = $out['found-file-path'];
            include_once( $filePath );
            $class = $out['type'] . 'ShopAccountHandler';
            $accountHandler = new $class( );
        }
        else
        {
            $accountHandler = new eZDefaultShopAccountHandler();
        }
        return $accountHandler;
    }
}

?>
