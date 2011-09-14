<?php
//
// Created on: <01-Mar-2005 15:47:23 ks>
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

$http = eZHTTPTool::instance();
$module = $Params['Module'];

$allSettingsList = $module->actionParameter( 'AllSettingsList' );

if ( $module->hasActionParameter( 'SelectedList' ) )
    $selectedList = $module->actionParameter( 'SelectedList' );
else
    $selectedList=array();

$siteAccess = $module->actionParameter( 'SiteAccess' );
if ( !$siteAccess )
    $siteAccess = 'global_override';

eZPreferences::setValue( 'admin_quicksettings_siteaccess', $siteAccess );

$iniFiles = array();

foreach( $allSettingsList as $index => $setting )
{
    $settingArray = explode( ';', $setting );

    if ( !array_key_exists( $settingArray[2], $iniFiles ) )
        $iniFiles[$settingArray[2]] = array();

    $iniFiles[$settingArray[2]][] = array ( $settingArray[0], $settingArray[1], in_array( $index, $selectedList ) );
}
unset( $setting );

$iniPath = ( $siteAccess == "global_override" ) ? "settings/override" : "settings/siteaccess/$siteAccess";

foreach( $iniFiles as $fileName => $settings )
{
    $ini = new eZINI( $fileName . '.append', $iniPath, null, null, null, true, true );
    $baseIni = eZINI::instance( $fileName );

    foreach( $settings as $setting )
    {
        if ( $ini->hasVariable( $setting[0], $setting[1] ) )
            $value = $ini->variable( $setting[0], $setting[1] );
        else
            $value = $baseIni->variable( $setting[0], $setting[1] );

        if ( $value == 'true' || $value == 'false' )
            $ini->setVariable( $setting[0], $setting[1], $setting[2] ? 'true' : 'false' );
        else
            $ini->setVariable( $setting[0], $setting[1], $setting[2] ? 'enabled' : 'disabled' );
    }

    if ( !$ini->save() )
    {
        eZDebug::writeError( "Can't save ini file: $iniPath/$fileName.append" );
    }

    unset( $baseIni );
    unset( $ini );

    // Remove variable from the global override
    if ( $siteAccess != "global_override" )
    {
        $ini = new eZINI( $fileName . '.append', "settings/override", null, null, null, true, true );
        foreach( $settings as $setting )
        {
            if ( $ini->hasVariable( $setting[0], $setting[1] ) )
                $ini->removeSetting( $setting[0], $setting[1] );
        }
        if ( !$ini->save() )
        {
            eZDebug::writeError( "Can't save ini file: $iniPath/$fileName.append" );
        }

        unset($ini);
    }
}

$uri = $http->postVariable( 'RedirectURI', $http->sessionVariable( 'LastAccessedModifyingURI', '/' ) );
$module->redirectTo( $uri );

?>
