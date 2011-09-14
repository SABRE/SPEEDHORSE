<?php
//
// Definition of eZExchangeRatesUpdateHandler class
//
// Created on: <12-Mar-2006 13:06:15 dl>
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

class eZExchangeRatesUpdateHandler
{
    const OK = 0;
    const CANT_CREATE_HANDLER = 1;
    const FAILED = 2;
    const EMPTY_RATE_LIST = 3;
    const UNKNOWN_BASE_CURRENCY = 4;
    const INVALID_BASE_CROSS_RATE = 5;

    function eZExchangeRatesUpdateHandler()
    {
        $this->RateList = false;
        $this->BaseCurrency = false;

        $this->initialize();
    }

    function initialize( $params = array() )
    {
        if ( !isset( $params['BaseCurrency'] ) )
            $params['BaseCurrency'] = '';

        $this->setBaseCurrency( $params['BaseCurrency'] );
    }

    /*!
     \static
    */
    static function create( $handlerName = false )
    {
        $shopINI = eZINI::instance( 'shop.ini' );
        if ( $handlerName === false)
        {
           if ( $shopINI->hasVariable( 'ExchangeRatesSettings', 'ExchangeRatesUpdateHandler' ) )
               $handlerName = $shopINI->variable( 'ExchangeRatesSettings', 'ExchangeRatesUpdateHandler' );
        }

        $handlerName = strtolower( $handlerName );

        $dirList = array();
        $repositoryDirectories = $shopINI->variable( 'ExchangeRatesSettings', 'RepositoryDirectories' );
        $extensionDirectories = $shopINI->variable( 'ExchangeRatesSettings', 'ExtensionDirectories' );

        $baseDirectory = eZExtension::baseDirectory();
        foreach ( $extensionDirectories as $extensionDirectory )
        {
            if ( !empty( $extensionDirectory ) )
                $dirList[] = $baseDirectory . '/' . $extensionDirectory . '/exchangeratehandlers';
        }

        foreach ( $repositoryDirectories as $repositoryDirectory )
        {
            if ( !empty( $repositoryDirectory ) )
                $dirList[] = $repositoryDirectory;
        }

        $foundHandler = false;
        foreach ( $dirList as $dir )
        {
            $includeFile = "$dir/$handlerName/{$handlerName}handler.php";

            if ( file_exists( $includeFile ) )
            {
                $foundHandler = true;
                break;
            }
        }

        if ( !$foundHandler )
        {
            eZDebug::writeError( "Exchange rates update handler '$handlerName' not found, " .
                                   "searched in these directories: " .
                                   implode( ', ', $dirList ),
                                 'eZExchangeRatesUpdateHandler::create' );
            return false;
        }

        require_once( $includeFile );
        $className = $handlerName . 'handler';
        return new $className;
    }

    function rateList()
    {
        return $this->RateList;
    }

    function setRateList( $rateList )
    {
        $this->RateList = $rateList;
    }

    function baseCurrency()
    {
        return $this->BaseCurrency;
    }

    function setBaseCurrency( $baseCurrency )
    {
        $this->BaseCurrency = $baseCurrency;
    }

    function requestRates()
    {
        $error = array( 'code' => self::FAILED,
                        'description' => ezpI18n::tr( 'kernel/shop', "eZExchangeRatesUpdateHandler: you should reimplement 'requestRates' method" ) );

        return $error;
    }

    public $RateList;
    public $BaseCurrency;
}

?>
