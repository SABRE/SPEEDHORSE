#!/usr/bin/env php
<?php
//
// Created on: <9-Jul-2007 14:00:25 dp>
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
$script = eZScript::instance( array( 'description' => ( "eZ Publish Country update script\n\n" .
                                                        "Upgrades db table in addition with upgrade from 3.9.2 to 3.9.3\n" .
                                                        "Fixes bug with aplying VAT rules" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );
$script->startup();

$options = $script->getOptions(  );

$script->initialize();

$db = eZDB::instance();

$countries = $db->arrayQuery( "SELECT country_code from ezvatrule;" );
$iniCountries = eZCountryType::fetchCountryList();

$updatedRules = 0;

foreach ( $countries as $country )
{
    foreach ( $iniCountries as $iniCountry )
    {
        if ( $iniCountry['Name'] == $country['country_code'] )
        {
            $countryName = $country['country_code'];
            $countryCode = $iniCountry['Alpha2'];
            $db->query( "UPDATE ezvatrule SET country_code='" . $db->escapeString( $countryCode ) . "' WHERE country_code='" . $db->escapeString( $countryName ) . "'" );
            $updatedRules++;
        }
    }
}

$cli->output( 'Updated VAT rules: ' . $updatedRules );

$script->shutdown();

?>
