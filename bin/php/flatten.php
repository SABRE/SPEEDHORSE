#!/usr/bin/env php
<?php
//
// Created on: <19-Dec-2003 14:34:55 amos>
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

require 'autoload.php';

$cli = eZCLI::instance();
$endl = $cli->endlineString();

$script = eZScript::instance( array( 'description' => ( "eZ Publish database flattening.\n\n" .
                                                        "Will remove data that is not considered currently in use to minimize the amount of database data it consumes\n" .
                                                        "\n" .
                                                        "Possible values for NAME is:\n" .
                                                        "contentobject, contentclass, workflow, role or all (for all items)\n" .
                                                        "flatten.php -s admin contentobject"),
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
    $cli->error( "Missing NAME value ( could be contentobject, contentclass, workflow, role or all )" );
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
    changeSiteAccessSetting( $siteAccess );
}


$flattenAllItems = false;
$flattenItems = array();
$flatten = array( 'contentobject' => false,
                  'contentclass' => false,
                  'workflow' => false,
                  'role' => false );

foreach ( $options['arguments'] as $arg )
{

    $item = strtolower( $arg );
    if ( $item == 'all' )
        $flattenAllItems = true;
    else
        $flattenItems[] = $item;
}

if ( $flattenAllItems )
{
    $names = array_keys( $flatten );
    foreach ( $names as $name )
    {
        $flatten[$name] = true;
    }
}
else
{
    if ( count( $flattenItems ) == 0 )
    {
        help();
        exit;
    }
    foreach ( $flattenItems as $name )
    {
        $flatten[$name] = true;
    }
}

function changeSiteAccessSetting( $siteAccess )
{
    global $isQuiet;
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $siteAccess) )
    {
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteAccess for nice url update" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $siteAccess does not exist, using default siteaccess" );
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

if ( $flatten['contentobject'] )
{
    $cli->output( "Removing non-published content object versions" );
    eZContentObjectVersion::removeVersions();
}

if ( $flatten['contentclass'] )
{
    $cli->output( "Removing temporary content classes" );
    eZContentClass::removeTemporary();
}

if ( $flatten['workflow'] )
{
    $cli->output( "Removing temporary workflows" );
    eZWorkflow::removeTemporary();
}

if ( $flatten['role'] )
{
    $cli->output( "Removing temporary roles" );
    eZRole::removeTemporary();
}


$script->shutdown();

?>
