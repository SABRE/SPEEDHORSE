<?php
//
// Created on: <04-Feb-2004 21:56:50 kk>
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

$Module = $Params['Module'];


$tpl = eZTemplate::factory();

$tpl->setVariable( 'md5_result', false );
$tpl->setVariable( 'upgrade_sql', false );

if ( $Module->isCurrentAction( 'MD5Check' ) )
{
    if ( !file_exists( eZMD5::CHECK_SUM_LIST_FILE ) )
    {
        $tpl->setVariable( 'md5_result', 'failed' );
        $tpl->setVariable( 'failure_reason',
                           ezpI18n::tr( 'kernel/setup', 'File %1 does not exist. '.
                                    'You should copy it from the recent eZ Publish distribution.',
                                    null, array( eZMD5::CHECK_SUM_LIST_FILE ) ) );
    }
    else
    {
        $checkResult = eZMD5::checkMD5Sums( eZMD5::CHECK_SUM_LIST_FILE );

        $extensionsDir = eZExtension::baseDirectory();
        foreach( eZextension::activeExtensions() as $activeExtension )
        {
            $extensionPath = "$extensionsDir/$activeExtension/";
            if ( file_exists( $extensionPath . eZMD5::CHECK_SUM_LIST_FILE ) )
            {
                $checkResult = array_merge( $checkResult, eZMD5::checkMD5Sums( $extensionPath . eZMD5::CHECK_SUM_LIST_FILE, $extensionPath ) );
            }
        }

        if ( count( $checkResult ) == 0 )
        {
            $tpl->setVariable( 'md5_result', 'ok' );
        }
        else
        {
            $tpl->setVariable( 'md5_result', $checkResult );
        }
    }
}

if ( $Module->isCurrentAction( 'DBCheck' ) )
{
    $db = eZDB::instance();
    $dbSchema = eZDbSchema::instance();
    // read original schema from dba file
    $originalSchema = eZDbSchema::read( 'share/db_schema.dba' );

    // merge schemas from all active extensions that declare some db schema
    $extensionsdir = eZExtension::baseDirectory();
    foreach( eZExtension::activeExtensions() as $activeextension )
    {
        if ( file_exists( $extensionsdir . '/' . $activeextension . '/share/db_schema.dba' ) )
        {
            if ( $extensionschema = eZDbSchema::read( $extensionsdir . '/' . $activeextension . '/share/db_schema.dba' ) )
            {
                $originalSchema = eZDbSchema::merge( $originalSchema, $extensionschema );
            }
        }
    }

    // transform schema to 'localized' version for current db
    // (we might as well convert $dbSchema to generic format and diff in generic format,
    // but eZDbSchemaChecker::diff does not know how to re-localize the generated sql
    $dbSchema->transformSchema( $originalSchema, true );
    $differences = eZDbSchemaChecker::diff( $dbSchema->schema( array( 'format' => 'local', 'force_autoincrement_rebuild' => true ) ), $originalSchema );
    $sqlDiff = $dbSchema->generateUpgradeFile( $differences );

    if ( strlen( $sqlDiff ) == 0 )
    {
        $tpl->setVariable( 'upgrade_sql', 'ok' );
    }
    else
    {
        $tpl->setVariable( 'upgrade_sql', $sqlDiff );
    }
}

$Result = array();
$Result['content'] = $tpl->fetch( "design:setup/systemupgrade.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/setup', 'System Upgrade' ) ) );
?>
