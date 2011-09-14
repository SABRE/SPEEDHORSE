<?php
//
// Definition of eZStepPackageLanguageOptions class
//
// Created on: <21-Feb-2007 17:27:57 dl>
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

/*!
  \class eZStepPackageLanguageOptions ezstep_package_language_options.php
  \brief The class eZStepPackageLanguageOptions does

*/

class eZStepPackageLanguageOptions extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepPackageLanguageOptions( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'package_language_options', 'Package language options' );
    }

    function processPostData()
    {
        $languageMap = array();
        if( $this->Http->hasPostVariable( 'eZSetupPackageLanguageMap' ) )
        {
            $languageMap = $this->Http->postVariable( 'eZSetupPackageLanguageMap' );
        }

        // Add site languages.
        $siteLanguageLocaleList = $this->PersistenceList['regional_info']['languages'];
        foreach( $siteLanguageLocaleList as $siteLanguage )
            $languageMap[$siteLanguage] = $siteLanguage;

        $this->PersistenceList['package_info']['language_map'] = $languageMap;

        return true;
    }

    function init()
    {
        /*
        if( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            return $this->kickstartContinueNextStep();
        }
        */

        //
        // Get all available languages
        //
        $languages = false;
        $defaultLanguage = false;
        $defaultExtraLanguages = false;

        eZSetupLanguageList( $languages, $defaultLanguage, $defaultExtraLanguages );


        //
        // Get info about package and site languages
        //
        $siteLanguageLocaleList = $this->PersistenceList['regional_info']['languages'];

        $packageNameList = array();
        $packageLanguageLocaleList = array();

        $sitePackageName = $this->chosenSitePackage();
        $sitePackage = eZPackage::fetch( $sitePackageName, false, false, false );
        if( is_object( $sitePackage ) )
        {
            $dependencies = $sitePackage->attribute( 'dependencies' );
            $requirements = $dependencies['requires'];

            foreach( $requirements as $req )
            {
                $packageNameList[] = $req['name'];
            }

            $packageLanguageLocaleList = eZPackage::languageInfoFromPackageList( $packageNameList, false );
        }

        // Explicitly add 'eng-GB' cause clean data is in 'eng-GB'.
        if( !in_array( 'eng-GB', $packageLanguageLocaleList ) )
            $packageLanguageLocaleList[] = 'eng-GB';
        //
        // Exclude languages which exist both in packges and site.
        //
        $packageLanguageLocaleList = array_diff( $packageLanguageLocaleList, $siteLanguageLocaleList );

        if( count( $packageLanguageLocaleList ) > 0 )
        {
            //
            // Get language names
            //
            $siteLanguageList = array();
            $packageLanguageList = array();
            foreach( $languages as $language )
            {
                $locale = $language->attribute( 'locale_code' );
                $name = $language->attribute( 'intl_language_name' );

                if( in_array( $locale, $siteLanguageLocaleList ) )
                {
                    $siteLanguageList[] = array( 'locale' => $locale,
                                                 'name' => $name );
                }

                if( in_array( $locale, $packageLanguageLocaleList ) )
                {
                    $packageLanguageList[] = array( 'locale' => $locale,
                                                    'name' => $name );
                }
            }

            $this->MissedPackageLanguageList = $packageLanguageList;
            $this->SiteLanguageList = $siteLanguageList;

            return false;
        }

        // There are no language conflicts => proceed with next step
        return true;
    }

    function display()
    {
        $packageLanguageList = $this->MissedPackageLanguageList;
        $siteLanguageList = $this->SiteLanguageList;

        $this->Tpl->setVariable( 'package_language_list', $packageLanguageList );
        $this->Tpl->setVariable( 'site_language_list', $siteLanguageList );

        $result = array();
        $result['content'] = $this->Tpl->fetch( "design:setup/init/package_language_options.tpl" );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Package language options' ),
                                        'url' => false ) );
        return $result;
    }

    public $MissedPackageLanguageList;
    public $SiteLanguageList;
}
?>
