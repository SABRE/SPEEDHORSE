<?php
//
// Definition of eZStylePackageCreator class
//
// Created on: <14-Dec-2005 12:39:59 ks>
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
  \ingroup package
  \class eZStylePackageCreator ezstylepackagecreator.php
  \brief The class eZStylePackageCreator does

*/

class eZExtensionPackageCreator extends eZPackageCreationHandler
{
    /*!
     Constructor
    */
    function eZExtensionPackageCreator( $id )
    {
        $steps = array();
        $steps[] = array( 'id' => 'extensionlist',
                          'name' => ezpI18n::tr( 'kernel/package', 'Extensions to include' ),
                          'methods' => array( 'initialize' => 'initializeExtensionName',
                                              'load' => 'loadExtensionName',
                                              'validate' => 'validateExtensionName',
                                              'commit' => 'commitExtensionName' ),
                          'template' => 'extension.tpl' );
        $steps[] = $this->packageInformationStep();
        $steps[] = $this->packageMaintainerStep();
        $steps[] = $this->packageChangelogStep();
        $this->eZPackageCreationHandler( $id,
                                         ezpI18n::tr( 'kernel/package', 'Extension export' ),
                                         $steps );
    }

    function finalize( &$package, $http, &$persistentData )
    {
        $this->createPackage( $package, $http, $persistentData, $cleanupFiles, false );

        $extensionHandler = eZPackage::packageHandler( 'ezextension' );

        $extensionList = $persistentData['extensionlist'];
        foreach ( $extensionList as $extensionName )
        {
            $extensionHandler->addExtension( $package, $extensionName );
        }

        $package->setAttribute( 'is_active', true );
        $package->store();
    }

    /*!
     \return \c 'import'
    */
    function packageInstallType( $package, &$persistentData )
    {
        return 'install';
    }

    /*!
     Returns \c 'stable', site style packages are always stable.
    */
    function packageInitialState( $package, &$persistentData )
    {
        return 'stable';
    }

    /*!
     \return \c 'sitestyle'.
    */
    function packageType( $package, &$persistentData )
    {
        return 'extension';
    }

    function initializeExtensionName( $package, $http, $step, &$persistentData, $tpl )
    {
    }

    function loadExtensionName( $package, $http, $step, &$persistentData, $tpl )
    {
        $extensionList = eZDir::findSubItems( eZExtension::baseDirectory(), 'dl' );
        $tpl->setVariable( 'extension_list', $extensionList );
    }

    function validateExtensionName( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $extensionList = array();

        if ( !$http->hasPostVariable( 'PackageExtensionNames' ) ||
             count( $http->postVariable( 'PackageExtensionNames' ) ) == 0 )
        {
            $errorList[] = array( 'field' => ezpI18n::tr( 'kernel/package', 'Extension list' ),
                                  'description' => ezpI18n::tr( 'kernel/package', 'You must select at least one extension' ) );
            return false;
        }
        return true;
    }

    function commitExtensionName( $package, $http, $step, &$persistentData, $tpl )
    {
        $persistentData['extensionlist'] = $http->postVariable( 'PackageExtensionNames' );
    }

    /*!
     Fetches the selected content classes and generates a name, summary and description from the selection.
    */
    function generatePackageInformation( &$packageInformation, $package, $http, $step, &$persistentData )
    {
        $extensionList = $persistentData['extensionlist'];
        $extensionCount = count( $extensionList );

        if ( $extensionCount == 1 )
        {
            $extensionName = $extensionList[0];
            $packageInformation['name'] = $extensionName;
            $packageInformation['summary'] = "$extensionName extension";
            $packageInformation['description'] = "This package contains the $extensionName eZ Publish extension";
        }
        else if ( $extensionCount > 1 )
        {
            $packageInformation['name'] = "$extensionCount Extensions";
            $packageInformation['summary'] = "Export of $extensionCount extensions";
            $description = "This package contains the following eZ Publish extensions: \n";
            foreach ( $extensionList as $extensionName )
            {
                $description .= "- $extensionName\n";
            }
            $packageInformation['description'] = $description;
        }
    }
}

?>
