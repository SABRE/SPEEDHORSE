<?php
//
// Definition of eZContentClassPackageCreator class
//
// Created on: <21-Nov-2003 12:39:59 amos>
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
  \class eZContentClassPackageCreator ezcontentclasspackagecreator.php
  \brief A package creator for content classes

*/

class eZContentClassPackageCreator extends eZPackageCreationHandler
{
    function eZContentClassPackageCreator( $id )
    {
        $steps = array();
        $steps[] = array( 'id' => 'class',
                          'name' => ezpI18n::tr( 'kernel/package', 'Content classes to include' ),
                          'methods' => array( 'initialize' => 'initializeClassData',
                                              'validate' => 'validateClassData',
                                              'commit' => 'commitClassData' ),
                          'template' => 'class.tpl' );
        $steps[] = $this->packageInformationStep();
        $steps[] = $this->packageMaintainerStep();
        $steps[] = $this->packageChangelogStep();
        $this->eZPackageCreationHandler( $id,
                                         ezpI18n::tr( 'kernel/package', 'Content class export' ),
                                         $steps );
    }

    /*!
     Creates the package and adds the selected content classes.
    */
    function finalize( &$package, $http, &$persistentData )
    {
        $this->createPackage( $package, $http, $persistentData, $cleanupFiles );

        $classHandler = eZPackage::packageHandler( 'ezcontentclass' );
        $classList = $persistentData['classlist'];
        foreach ( $classList as $classID )
        {
            $classHandler->addClass( $package, $classID );
        }
        $package->setAttribute( 'is_active', true );
        $package->store();
    }

    /*!
     Returns \c 'stable', content class packages are always stable.
    */
    function packageInitialState( $package, &$persistentData )
    {
        return 'stable';
    }

    /*!
     \return \c 'contentclass'.
    */
    function packageType( $package, &$persistentData )
    {
        return 'contentclass';
    }

    function initializeClassData( $package, $http, $step, &$persistentData, $tpl )
    {
    }

    /*!
     Checks if at least one content class has been selected.
    */
    function validateClassData( $package, $http, $currentStepID, &$stepMap, &$persistentData, &$errorList )
    {
        $classList = array();
        if ( $http->hasPostVariable( 'ClassList' ) )
            $classList = $http->postVariable( 'ClassList' );

        $persistentData['classlist'] = $classList;

        $result = true;
        if ( count( $classList ) == 0 )
        {
            $errorList[] = array( 'field' => ezpI18n::tr( 'kernel/package', 'Class list' ),
                                  'description' => ezpI18n::tr( 'kernel/package', 'You must select at least one class for inclusion' ) );
            $result = false;
        }
        return $result;
    }

    function commitClassData( $package, $http, $step, &$persistentData, $tpl )
    {
    }

    /*!
     Fetches the selected content classes and generates a name, summary and description from the selection.
    */
    function generatePackageInformation( &$packageInformation, $package, $http, $step, &$persistentData )
    {
        $classList = $persistentData['classlist'];

        if ( count( $classList ) == 1 )
        {
            $classID = $classList[0];
            $class = eZContentClass::fetch( $classID );
            if ( $class )
            {
                $packageInformation['name'] = $class->attribute( 'name' );
                $packageInformation['summary'] = 'Export of content class ' . $class->attribute( 'name' );
                $packageInformation['description'] = 'This package contains an exported definition of the content class ' . $class->attribute( 'name' ) . ' which can be imported to another eZ Publish site';
            }
        }
        else if ( count( $classList ) > 1 )
        {
            $classNames = array();
            foreach ( $classList as $classID )
            {
                $class = eZContentClass::fetch( $classID );
                if ( $class )
                {
                    $classNames[] = $class->attribute( 'name' );
                }
            }
            $packageInformation['name'] = count( $classList ) . ' Classes';
            $packageInformation['summary'] = 'Export of ' . count( $classList ) . ' content classes';
            $description = 'This package contains exported definitions of the following content classes:' . "\n";
            foreach ( $classNames as $className )
            {
                $description .= '- ' . $className . "\n";
            }
            $packageInformation['description'] = $description;
        }
    }
}

?>
