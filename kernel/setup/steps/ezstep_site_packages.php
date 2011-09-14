<?php
//
// Definition of eZStepSitePackages class
//
// Created on: <16-Apr-2004 10:53:35 amos>
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



/*!
  \class eZStepSitePackages ezstep_site_packages.php
  \brief The class eZStepSitePackages does

*/

class eZStepSitePackages extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSitePackages( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'site_packages', 'Site packages' );
    }

    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetup_site_packages' ) )
        {
            $sitePackages = $this->Http->postVariable( 'eZSetup_site_packages' );
            $this->PersistenceList['site_packages'] = $sitePackages;

            if ( $this->Http->hasPostVariable( 'AdditionalPackages' ) )
            {
                $this->PersistenceList['additional_packages'] = $this->Http->postVariable( 'AdditionalPackages' );
            }
            else
            {
                $this->PersistenceList['additional_packages'] = array();
            }
        }
        else
        {
            $this->ErrorMsg = ezpI18n::tr( 'design/standard/setup/init',
                                      'No packages chosen.' );
            return false;
        }
        return true;
    }

    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            $siteTypes = $this->chosenSiteTypes();
            $siteType = $siteTypes[0]['identifier'];

            $typeFunctionality = eZSetupFunctionality( $siteType );
            $requiredPackageList = $typeFunctionality['required'];
            $requiredPackages = array();
            foreach ( $requiredPackageList as $requiredPackage )
            {
                $requiredPackages[] = strtolower( $requiredPackage );
            }
            $this->PersistenceList['site_packages'] = $requiredPackages;

            $additionalPackages = $data['Packages'];
            foreach ( $additionalPackages as $key => $additionalPackage )
            {
                $additionalPackages[$key] = strtolower( $additionalPackage );
            }
            $this->PersistenceList['additional_packages'] = $additionalPackages;

            if ( ( count( $requiredPackages ) + count( $additionalPackages ) ) > 0 )
            {
                return $this->kickstartContinueNextStep();
            }
        }

        $this->ErrorMsg = false;
        return false; // Always show site package selection
    }

    function display()
    {
        $siteTypes = $this->chosenSiteTypes();
        $siteType = $siteTypes[0]['identifier'];

        $typeFunctionality = eZSetupFunctionality( $siteType );
        $requiredPackageList = $typeFunctionality['required'];
        $requiredPackages = array();
        foreach ( $requiredPackageList as $requiredPackage )
        {
            $requiredPackages[] = strtolower( $requiredPackage );
        }

        $packageArray = eZPackage::fetchPackages( array( 'repository_id' => 'addons' ) );

        $requiredPackageInfoArray = array();
        $packageInfoArray = array();
        foreach ( $packageArray as $package )
        {
            if ( in_array( strtolower( $package->attribute( 'name' ) ), $requiredPackages ) )
            {
                $requiredPackageInfoArray[] = array( 'identifier' => $package->attribute( 'name' ),
                                                     'name' => $package->attribute( 'summary' ),
                                                     'description' => $package->attribute( 'description' ) );
            }
            else
            {
                $packageInfoArray[] = array( 'identifier' => $package->attribute( 'name' ),
                                             'name' => $package->attribute( 'summary' ),
                                             'description' => $package->attribute( 'description' ) );
            }
        }

        $recommended = array();
        if ( isset( $typeFunctionality['recommended'] ) )
            $recommended = $typeFunctionality['recommended'];

        if ( isset( $this->PersistenceList['additional_packages'] ) )
            $recommended = array_unique( array_merge( $recommended,
                                                      $this->PersistenceList['additional_packages'] ) );

        $this->Tpl->setVariable( 'site_types', $siteTypes );
        $this->Tpl->setVariable( 'recommended_package_array', $recommended );
        $this->Tpl->setVariable( 'error', $this->ErrorMsg );
        $this->Tpl->setVariable( 'required_package_array', $requiredPackageInfoArray );
        $this->Tpl->setVariable( 'package_array', $packageInfoArray );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_packages.tpl' );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Site functionality' ),
                                        'url' => false ) );
        return $result;

    }

    public $Error = 0;
}

?>
