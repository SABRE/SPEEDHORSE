<?php
//
// Definition of eZWizardBaseClassLoader class
//
// Created on: <12-Nov-2004 16:24:31 kk>
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
  \class eZWizardBaseClassLoader ezwizardbaseclassloader.php
  \brief The class eZWizardBaseClassLoader does

*/

class eZWizardBaseClassLoader
{
    /*!
     \static
     Create new specified class
    */
    static function createClass( $tpl,
                                 $module,
                                 $stepArray,
                                 $basePath,
                                 $storageName = false,
                                 $metaData = false )
    {
        if ( !$storageName )
        {
            $storageName = 'eZWizard';
        }

        if ( !$metaData )
        {
            $http = eZHTTPTool::instance();
            $metaData = $http->sessionVariable( $storageName . '_meta' );
        }

        if ( !isset( $metaData['current_step'] ) ||
             $metaData['current_step'] < 0 )
        {
            $metaData['current_step'] = 0;
            eZDebug::writeNotice( 'Setting wizard step to : ' . $metaData['current_step'], __METHOD__ );
        }
        $currentStep = $metaData['current_step'];

        if ( count( $stepArray ) <= $currentStep )
        {
            eZDebug::writeError( 'Invalid wizard step count: ' . $currentStep, __METHOD__ );
            return false;
        }

        $filePath = $basePath . $stepArray[$currentStep]['file'];
        if ( !file_exists( $filePath ) )
        {
            eZDebug::writeError( 'Wizard file not found : ' . $filePath, __METHOD__ );
            return false;
        }

        include_once( $filePath );

        $className = $stepArray[$currentStep]['class'];
        eZDebug::writeNotice( 'Creating class : ' . $className, __METHOD__ );
        $returnClass =  new $className( $tpl, $module, $storageName );

        if ( isset( $stepArray[$currentStep]['operation'] ) )
        {
            $operation = $stepArray[$currentStep]['operation'];
            return $returnClass->$operation();
            eZDebug::writeNotice( 'Running : "' . $className . '->' . $operation . '()". Specified in StepArray', __METHOD__ );
        }

        if ( isset( $metaData['current_stage'] ) )
        {
            $returnClass->setMetaData( 'current_stage', $metaData['current_stage'] );
            eZDebug::writeNotice( 'Setting wizard stage to : ' . $metaData['current_stage'], __METHOD__ );
        }

        return $returnClass;
    }
}

?>
