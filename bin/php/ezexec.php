#!/usr/bin/env php
<?php
//
// Created on: <03-May-2004 07:52:57 amos>
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
$script = eZScript::instance( array( 'description' => ( "eZ Publish Script Executor\n\n" .
                                                        "Allows execution of simple PHP scripts which uses eZ Publish functionality,\n" .
                                                        "when the script is called all necessary initialization is done\n" .
                                                        "\n" .
                                                        "ezexec.php myscript.php" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "",
                                "[scriptfile]",
                                array() );
$script->initialize();

if ( count( $options['arguments'] ) < 1 )
{
    $script->shutdown( 1, "Missing script file" );
}

$scriptFile = $options['arguments'][0];

if ( !file_exists( $scriptFile ) )
    $script->shutdown( 1, "Could execute the script '$scriptFile', file was not found" );

$retCode = include( $scriptFile );

if ( $retCode != 1 )
    $script->setExitCode( 1 );

$script->shutdown();

?>
