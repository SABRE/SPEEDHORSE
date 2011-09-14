#!/usr/bin/env php
<?php
//
// Created on: <21-Aug-2009 11:52:57 ar>
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

set_time_limit( 0 );
$isQuiet = false;

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Session Garbage Collector\n\n" .
                                                        "Allows manual cleaning up expired sessions as defined by site.ini[Session]SessionTimeout\n" .
                                                        "\n" .
                                                        "./bin/php/ezsessiongc.php" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "",
                                "[]",
                                array() );
$script->initialize();

if ( !$isQuiet )
    $cli->notice( "Cleaning up expired sessions." );

// Functions for session to make sure baskets are cleaned up
function eZSessionBasketGarbageCollector( $db, $time )
{
    eZBasket::cleanupExpired( $time );
}

// Fill in hooks
eZSession::addCallback( 'gc_pre', 'eZSessionBasketGarbageCollector');

eZSession::garbageCollector();

$script->shutdown();

?>
