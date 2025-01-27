#!/usr/bin/env php
<?php
//
// Created on: <18-Dec-2003 17:44:15 amos>
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

require 'autoload.php';

set_time_limit( 0 );

$cli = eZCLI::instance();
$endl = $cli->endlineString();

$script = eZScript::instance( array( 'description' => ( "eZ Publish database cleanup.\n\n" .
                                                        "Will cleanup various data from the currently used database in eZ Publish\n" .
                                                        "\n" .
                                                        "Possible values for NAME is:\n" .
                                                        "session, expired_session, preferences, browse, tipafriend, shop, forgotpassword, workflow,\n" .
                                                        "collaboration, collectedinformation, notification, searchstats or all (for all items)\n" .
                                                        "cleanup.php -s admin session"),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[db-host:][db-user:][db-password:][db-database:][db-type:|db-driver:][sql]",
                                "[name]",
                                array( 'db-host' => "Database host",
                                       'db-user' => "Database user",
                                       'db-password' => "Database password",
                                       'db-database' => "Database name",
                                       'db-driver' => "Database driver",
                                       'db-type' => "Database driver, alias for --db-driver",
                                       'sql' => "Display sql queries"
                                       ) );
$script->initialize();

if ( count( $options['arguments'] ) < 1 )
{
    $cli->error( "Missing NAME value ( could be session, expired_session, preferences, browse, tipafriend, shop, forgotpassword, workflow,\n" .
                 "collaboration, collectedinformation, notification, searchstats or all )" );
    $script->shutdown( 1 );
}

$dbUser = $options['db-user'] ? $options['db-user'] : false;
$dbPassword = $options['db-password'] ? $options['db-password'] : false;
$dbHost = $options['db-host'] ? $options['db-host'] : false;
$dbName = $options['db-database'] ? $options['db-database'] : false;
$dbImpl = $options['db-driver'] ? $options['db-driver'] : false;
$showSQL = $options['sql'] ? true : false;
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;

if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
}

$cleanAllItems = false;
$clean = array( 'session' => false,
                'expired_session' => false,
                'preferences' => false,
                'browse' => false,
                'tipafriend' => false,
                'shop' => false,
                'forgotpassword' => false,
                'workflow' => false,
                'collaboration' => false,
                'collectedinformation' => false,
                'notification' => false,
                'searchstats' => false );

foreach ( $options['arguments'] as $arg )
{

    $item = strtolower( $arg );
    if ( $item == 'all' )
        $cleanAllItems = true;
    else
        $cleanItems[] = $item;
}

if ( $cleanAllItems )
{
    $names = array_keys( $clean );
    foreach ( $names as $name )
    {
        $clean[$name] = true;
    }
}
else
{
    if ( count( $cleanItems ) == 0 )
    {
        help();
        $script->shutdown( 0 );
    }
    foreach ( $cleanItems as $name )
    {
        $clean[$name] = true;
    }
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for database cleanup" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

$db = eZDB::instance();
if ( $dbHost or $dbName or $dbUser or $dbImpl )
{
    $params = array();
    if ( $dbHost !== false )
        $params['server'] = $dbHost;
    if ( $dbUser !== false )
    {
        $params['user'] = $dbUser;
        $params['password'] = '';
    }
    if ( $dbPassword !== false )
        $params['password'] = $dbPassword;
    if ( $dbName !== false )
        $params['database'] = $dbName;
    $db = eZDB::instance( $dbImpl, $params, true );
    eZDB::setInstance( $db );
}

$db->setIsSQLOutputEnabled( $showSQL );

if ( $clean['session'] )
{
    if ( !eZSession::getHandlerInstance()->hasBackendAccess() )
    {
        $cli->output( "Could not remove sessions, current session handler does not support session cleanup (not backend based)." );
    }
    else
    {
        $cli->output( "Removing all sessions" );
        eZSession::cleanup();
    }
}

if ( $clean['expired_session'] )
{
    if ( !eZSession::getHandlerInstance()->hasBackendAccess() )
    {
        $cli->output( "Could not remove expired sessions, current session handler does not support session garbage collection (not backend based)." );
    }
    else
    {
        $cli->output( "Removing expired sessions,", false );
        eZSession::garbageCollector();
        $activeCount = eZSession::countActive();
        $cli->output( " " . $cli->stylize( 'emphasize', $activeCount ) . " left" );
    }
}

if ( $clean['preferences'] )
{
    $cli->output( "Removing all preferences" );
    eZPreferences::cleanup();
}

if ( $clean['browse'] )
{
    $cli->output( "Removing all recent items and bookmarks for browse page" );
    eZContentBrowseRecent::cleanup();
    eZContentBrowseBookmark::cleanup();
}

if ( $clean['tipafriend'] )
{
    $cli->output( "Removing all counters for tip-a-friend" );
    eZTipafriendCounter::cleanup();
}

if ( $clean['shop'] )
{
    $cli->output( "Removing all baskets" );
    eZBasket::cleanup();
    $cli->output( "Removing all wishlists" );
    eZWishList::cleanup();
    $cli->output( "Removing all orders" );
    eZOrder::cleanup();
    $productCount = eZPersistentObject::count( eZProductCollection::definition() );
    if ( $productCount > 0 )
    {
        $cli->warning( "$productCount product collections still exists, must be a leak" );
    }
}

if ( $clean['forgotpassword'] )
{
    $cli->output( "Removing all forgot password requests" );
    eZForgotPassword::cleanup();
}

if ( $clean['workflow'] )
{
    $cli->output( "Removing all workflow processes and operation mementos" );
    eZOperationMemento::cleanup();
    eZWorkflowProcess::cleanup();
}

if ( $clean['collaboration'] )
{
    $cli->output( "Removing all collaboration elements" );
    eZCollaborationItem::cleanup();
}

if ( $clean['collectedinformation'] )
{
    $cli->output( "Removing all collected information" );
    eZInformationCollection::cleanup();
}

if ( $clean['notification'] )
{
    $cli->output( "Removing all notifications events" );
    eZNotificationEvent::cleanup();
    eZNotificationCollection::cleanup();
    eZNotificationEventFilter::cleanup();
}

if ( $clean['searchstats'] )
{
    $cli->output( "Removing all search statistics" );
    eZSearchLog::removeStatistics();
}


$script->shutdown();

?>
