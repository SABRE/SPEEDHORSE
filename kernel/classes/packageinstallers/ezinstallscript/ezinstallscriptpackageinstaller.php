<?php
//
// Definition of eZInstallScriptPackageInstaller class
//
// Created on: <16-Feb-2006 12:39:59 ks>
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
  \class eZInstallScriptPackageInstaller ezcontentclasspackageinstaller.php
*/

class eZInstallScriptPackageInstaller extends eZPackageInstallationHandler
{
     /*
      Constructor should be implemented in the child class
        and call the constructor of eZPackageInstallationHandler.
     */
    function eZInstallScriptPackageInstaller( $package, $type, $installItem )
    {
    }
    /*!
     Returns \c 'stable', content class packages are always stable.
    */
    function packageInitialState( $package, &$persistentData )
    {
        return 'stable';
    }

    function customInstallHandlerInfo( $package, $installItem )
    {
        $return = array();

        $itemPath = $package->path() . '/' . $installItem['sub-directory'];
        $xmlPath = $itemPath . '/' . $installItem['filename'] . '.xml';

        $dom = $package->fetchDOMFromFile( $xmlPath );
        if ( $dom )
        {
            $mainNode = $dom->documentElement;
            $return['file-path'] = $itemPath . '/' . $mainNode->getAttribute( 'filename' );
            $return['classname'] = $mainNode->getAttribute( 'classname' );
        }

        return $return;
    }

    function stepTemplate( $package, $installItem, $step )
    {
        $itemPath = $package->path() . '/' . $installItem['sub-directory'];
        $stepTemplatePath = $itemPath . '/templates';

        return array( 'name' => $step['template'],
                      'path' => $stepTemplatePath );
    }
}
?>
