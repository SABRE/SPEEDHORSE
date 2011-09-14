<?php
//
// Definition of Basket_cleanup class
//
// Created on: <14-Jun-2005 14:44:49 amos>
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

$ini = eZINI::instance();

// Check if this should be run in a cronjob
$useCronjob = $ini->variable( 'Session', 'BasketCleanup' ) == 'cronjob';
if ( !$useCronjob )
    return;

// Only do basket cleanup once in a while
$freq = $ini->variable( 'Session', 'BasketCleanupAverageFrequency' );
if ( mt_rand( 1, max( $freq, 1 ) ) != 1 )
    return;

$maxTime = $ini->variable( 'Session', 'BasketCleanupTime' );
$idleTime = $ini->variable( 'Session', 'BasketCleanupIdleTime' );
$fetchLimit = $ini->variable( 'Session', 'BasketCleanupFetchLimit' );

if ( !$isQuiet )
    $cli->output( "Cleaning up expired baskets" );
eZDBGarbageCollector::collectBaskets( $maxTime, $idleTime, $fetchLimit );

?>
