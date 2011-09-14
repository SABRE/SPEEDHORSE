<?php
//
// Definition of eZPackageoperator class
//
// Created on: <16-Oct-2003 10:51:28 wy>
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
  \class eZPackageOperator ezpackageoperator.php
  \brief The class eZPackageOperator does

*/

class eZPackageOperator
{
    /*!
     Constructor
    */
    function eZPackageOperator( $name = 'ezpackage' )
    {
        $this->Operators = array( $name );
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'class' => array( 'type' => 'string',
                                        'required' => true,
                                        'default' => false ),
                      'data' => array( 'type' => 'string',
                                       'required' => false,
                                       'default' => false ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $package = $operatorValue;
        $class = $namedParameters['class'];
        switch ( $class )
        {
            case 'thumbnail':
            {
                if ( $operatorValue instanceof eZPackage )
                {
                    if ( !is_array( $fileList = $operatorValue->fileList( 'default' ) ) )
                        $fileList = array();
                    foreach ( $fileList as $file )
                    {
                        $fileType = $file["type"];
                        if ( $fileType == 'thumbnail' )
                        {
                            $operatorValue = $operatorValue->fileItemPath( $file, 'default' );
                            return;
                        }
                    }
                    $operatorValue = false;
                }
            } break;

            case 'filepath':
            {
                if ( $operatorValue instanceof eZPackage )
                {
                    $variableName = $namedParameters['data'];
                    $fileList = $operatorValue->fileList( 'default' );
                    foreach ( $fileList as $file )
                    {
                        $fileIdentifier = $file["variable-name"];
                        if ( $fileIdentifier == $variableName )
                        {
                            $operatorValue = $operatorValue->fileItemPath( $file, 'default' );
                            return;
                        }
                    }
                    $tpl->error( $operatorName,
                                 "No filepath found for variable $variableName in package " . $package->attribute( 'name' ) );
                    $operatorValue = false;
                }
            } break;

            case 'fileitempath':
            {
                if ( $operatorValue instanceof eZPackage )
                {
                    $fileItem = $namedParameters['data'];
                    $operatorValue = $operatorValue->fileItemPath( $fileItem, 'default' );
                }
            } break;

            case 'documentpath':
            {
                if ( $package instanceof eZPackage )
                {
                    $documentName = $namedParameters['data'];
                    $documentList = $package->attribute( 'documents' );
                    foreach ( array_keys( $documentList ) as $key )
                    {
                        $document =& $documentList[$key];
                        $name = $document["name"];
                        if ( $name == $documentName )
                        {
                            $documentFilePath = $package->path() . '/' . eZPackage::documentDirectory() . '/' . $document['name'];
                            $operatorValue = $documentFilePath;
                            return;
                        }
                    }
                    $tpl->error( $operatorName,
                                 "No documentpath found for document $documentName in package " . $package->attribute( 'name' ) );
                    $operatorValue = false;
                }
            } break;

            case 'dirpath':
            {
                $dirPath = $operatorValue->currentRepositoryPath() . "/" . $operatorValue->attribute( 'name' );
                $operatorValue = $dirPath;
            } break;

            default:
                $tpl->error( $operatorName, "Unknown package operator name: '$class'" );
            break;
        }
    }
    /// \privatesection
    public $Operators;
}

?>
