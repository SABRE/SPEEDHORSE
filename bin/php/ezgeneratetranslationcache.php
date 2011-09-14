#!/usr/bin/env php
<?php
//
// Created on: <6-Apr-2007 15:00:00 dl>
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

// Generate caches for translations
// file  bin/php/ezgeneratetranslationcache.php


/**************************************************************
* script initializing                                         *
***************************************************************/

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "\n" .
                                                        "This script will generate caches for translations.\n" .
                                                        "Default usage: ./bin/php/ezgeneratetranslationcache -s setup\n" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true,
                                     'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[ts-list:]",
                                      "",
                                      array( 'ts-list' => "A list of translations to generate caches for, for example 'rus-RU nor-NO'\n".
                                                          "By default caches for all translations will be generated" ),
                                      false,
                                      array( 'user' => true )
                                     );
$script->initialize();

/**************************************************************
* process options                                             *
***************************************************************/

//
// 'ts-list' option
//
$translations = isset( $scriptOptions['ts-list'] ) ? explode( ' ', $scriptOptions['ts-list'] ) : array();
$translations = eZTSTranslator::fetchList( $translations );


/**************************************************************
* do the work
***************************************************************/

$cli->output( $cli->stylize( 'blue', "Processing: " ), false );

$ini = eZINI::instance();

foreach( $translations as $translation )
{
    $cli->output( "$translation->Locale ", false );

    $ini->setVariable( 'RegionalSettings', 'Locale', $translation->Locale );
    eZTranslationCache::resetGlobals();

    $translation->load( '' );
}

$cli->output( "", true );

$script->shutdown( 0 );

?>
