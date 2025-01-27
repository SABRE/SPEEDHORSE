#!/usr/bin/env php
<?php
//
// Created on: <02-Mar-2004 20:10:18 amos>
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

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Template Compiler\n" .
                                                         "\n" .
                                                         "./bin/php/eztc.php -snews --www-dir='/mypath' --index-file='/index.php' --access-path='news'" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[compile-directory:][www-dir:][index-file:][access-path:][force][full-url][no-full-url]",
                                "",
                                array( 'force' => "Force compilation of template whether it has changed or not",
                                       'compile-directory' => "Where to place compiled files,\ndefault is template/compiled in current cache directory",
                                       'full-url' => "Makes sure generated urls have http:// in them (i.e. global), used mainly by sites that include the eZ Publish HTML (e.g payment gateways)",
                                       'no-full-url' => "Makes sure generated urls are relative to the site. (default)",
                                       'www-dir' => "The part before the index.php in your URL, you should supply this if you are running in non-virtualhost mode",
                                       'index-file' => "The name of your index.php if you are running in non-virtualhost mode",
                                       'access-path' => "Extra access path" ) );
$sys = eZSys::instance();

$forceCompile = false;
$useFullURL = false;

if ( $options['www-dir'] )
{
    $sys->WWWDir = $options['www-dir'];
}
if ( $options['index-file'] )
{
    $sys->IndexFile = $options['index-file'];
}
if ( $options['access-path'] )
{
    $sys->AccessPath = array( $options['access-path'] );
}
if ( $options['force'] )
{
    $forceCompile = true;
}
if ( $options['full-url'] )
{
    $useFullURL = true;
}
if ( $options['no-full-url'] )
{
    $useFullURL = false;
}

$script->initialize();

$http = eZHTTPTool::instance();
$http->UseFullUrl = $useFullURL;


if ( count( $options['arguments'] ) > 0 )
{
    $ini = eZINI::instance();
    $tpl = eZTemplate::factory();

    $fileList = $options['arguments'];

    $script->setIterationData( '.', '~' );
    $script->setShowVerboseOutput( true );
    if ( $forceCompile )
        eZTemplateCompiler::setSettings( array( 'generate' => true ) );

    $files = array();
    foreach ( $fileList as $file )
    {
        $filename = basename( $file );
        if ( preg_match( "!^.+~$|^/?#.+#$|^\..+$!", $filename ) )
            continue;
        $files[] = $file;
    }

    $script->resetIteration( count( $files ) );
    foreach ( $files as $file )
    {
        if ( is_dir( $file ) )
        {
            $script->iterate( $cli, true, "Skipping directory: " . $cli->stylize( 'dir', $file ) );
        }
        else
        {
            $status = $tpl->compileTemplateFile( $file );
            $text = false;
            if ( $status )
                $text = "Compiled template file: " . $cli->stylize( 'file', $file );
            else
                $text = "Compilation failed: " . $cli->stylize( 'file', $file );
            $script->iterate( $cli, $status, $text );
        }
    }
}
else
{
    $ini = eZINI::instance();
    $standardDesign = $ini->variable( "DesignSettings", "StandardDesign" );
    $siteDesign = $ini->variable( "DesignSettings", "SiteDesign" );
    $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

    $designList = array_merge( array( $standardDesign ), $additionalSiteDesignList, array( $siteDesign ) );

    $tpl = eZTemplate::factory();

    $script->setIterationData( '.', '~' );
    if ( $forceCompile )
        eZTemplateCompiler::setSettings( array( 'generate' => true ) );

    $extensionDirectory = eZExtension::baseDirectory();
    $designINI = eZINI::instance( 'design.ini' );
    $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );

    foreach ( $designList as $design )
    {
        $cli->output( "Compiling in design " . $cli->stylize( 'emphasize', $design ) );
        $baseDir = 'design/' . $design;

        $files = eZDir::recursiveFindRelative( '', "$baseDir/templates", "\.tpl" );
        $files = array_merge( $files, eZDir::recursiveFindRelative( '', "$baseDir/override/templates", "\.tpl" ) );

        foreach( $extensions as $extension )
        {
            $files = array_merge( $files, eZDir::recursiveFindRelative( '', "$extensionDirectory/$extension/$baseDir/templates", "\.tpl" ) );
            $files = array_merge( $files, eZDir::recursiveFindRelative( '', "$extensionDirectory/$extension/$baseDir/override/templates", "\.tpl" ) );
        }

        $script->resetIteration( count( $files ) );
        foreach ( $files as $file )
        {
            $status = $tpl->compileTemplateFile( $file );
            $text = false;
            if ( $status )
                $text = "Compiled template file: " . $cli->stylize( 'file', $file );
            else
                $text = "Compilation failed: " . $cli->stylize( 'file', $file );
            $script->iterate( $cli, $status, $text );
        }
    }
}

$script->shutdown();

?>
