<?php
//
// Definition of eZPackageType class
//
// Created on: <15-Oct-2003 13:17:04 wy>
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
  \class eZPackageType ezpackagetype.php
  \ingroup eZDatatype
  \brief The class eZPackageType does

*/


class eZPackageType extends eZDataType
{
    const DATA_TYPE_STRING = 'ezpackage';
    const TYPE_FIELD = 'data_text1';
    const TYPE_VARIABLE = '_ezpackage_type_';
    const VIEW_MODE_FIELD = 'data_int1';
    const VIEW_MODE_VARIABLE = '_ezpackage_view_mode_';

    /*!
     Constructor
    */
    function eZPackageType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', 'Package', 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
    }

    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezpackage_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_ezpackage_data_text_' . $contentObjectAttribute->attribute( 'id' ) );

            // Save in ini files if the package type is sitestyle.
            $classAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );
            if ( $classAttribute->attribute( self::TYPE_FIELD ) == 'sitestyle' )
            {
                $package = eZPackage::fetch( $data );
                if ( $package )
                {
                    $fileList = $package->fileList( 'default' );
                    foreach ( array_keys( $fileList ) as $key )
                    {
                        $file =& $fileList[$key];
                        $fileIdentifier = $file["variable-name"];
                        if ( $fileIdentifier == 'sitecssfile' )
                        {
                            $siteCSS = $package->fileItemPath( $file, 'default' );
                        }
                        else if ( $fileIdentifier == 'classescssfile' )
                        {
                            $classesCSS = $package->fileItemPath( $file, 'default' );
                        }
                    }
                    $currentSiteAccess = $http->hasPostVariable( 'CurrentSiteAccess' )
                                         ? $http->postVariable( 'CurrentSiteAccess' )
                                         : false;
                    $iniPath = 'settings/override';
                    if ( $currentSiteAccess != 'Global' and $currentSiteAccess !== false )
                    {
                        $data .= ':' . $currentSiteAccess;
                        $iniPath = 'settings/siteaccess/' . $currentSiteAccess;
                    }

                    $designINI = eZINI::instance( 'design.ini.append.php', $iniPath, null, false, null, true );
                    $designINI->setVariable( 'StylesheetSettings', 'SiteCSS', $siteCSS );
                    $designINI->setVariable( 'StylesheetSettings', 'ClassesCSS', $classesCSS );
                    $designINI->save();
                }
            }
            $contentObjectAttribute->setAttribute( 'data_text', $data );
        }
        return true;
    }

    /*!
     Does nothing since it uses the data_text field in the content object attribute.
     See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( $attribute )
    {
        $ini = eZINI::instance();
        // Delete compiled template
        $siteINI = eZINI::instance();
        if ( $siteINI->hasVariable( 'FileSettings', 'CacheDir' ) )
        {
            $cacheDir = $siteINI->variable( 'FileSettings', 'CacheDir' );
            if ( $cacheDir[0] == "/" )
            {
                $cacheDir = eZDir::path( array( $cacheDir ) );
            }
            else
            {
                if ( $siteINI->hasVariable( 'FileSettings', 'VarDir' ) )
                {
                    $varDir = $siteINI->variable( 'FileSettings', 'VarDir' );
                    $cacheDir = eZDir::path( array( $varDir, $cacheDir ) );
                }
            }
        }
        else if ( $siteINI->hasVariable( 'FileSettings', 'VarDir' ) )
        {
            $varDir = $siteINI->variable( 'FileSettings', 'VarDir' );
            $cacheDir = $ini->variable( 'FileSettings', 'CacheDir' );
            $cacheDir = eZDir::path( array( $varDir, $cacheDir ) );
        }
        else
        {
            $cacheDir =  eZSys::cacheDirectory();
        }
        $compiledTemplateDir = $cacheDir ."/template/compiled";
        eZDir::unlinkWildcard( $compiledTemplateDir . "/", "*pagelayout*.*" );

        // Expire template block cache
        eZContentCacheManager::clearTemplateBlockCacheIfNeeded( false );
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $packageTypeName = $base . self::TYPE_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $packageTypeName ) )
        {
            $packageTypeValue = $http->postVariable( $packageTypeName );
            $classAttribute->setAttribute( self::TYPE_FIELD, $packageTypeValue );
        }
        $packageViewModeName = $base . self::VIEW_MODE_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $packageViewModeName ) )
        {
            $packageViewModeValue = $http->postVariable( $packageViewModeName );
            $classAttribute->setAttribute( self::VIEW_MODE_FIELD, $packageViewModeValue );
        }
        return true;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $packageName = $contentObjectAttribute->attribute( "data_text" );
        $package = eZPackage::fetch( $packageName );
        return $package;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     Returns the content of the string for use as a title
    */
    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( 'data_text' ) ) != '';
    }

    function isIndexable()
    {
        return false;
    }

    function sortKey( $contentObjectAttribute )
    {
        return strtolower( $contentObjectAttribute->attribute( 'data_text' ) );
    }

    function sortKeyType()
    {
        return 'string';
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $type = $classAttribute->attribute( self::TYPE_FIELD );
        $dom = $attributeParametersNode->ownerDocument;
        $typeNode = $dom->createElement( 'type' );
        $typeNode->appendChild( $dom->createTextNode( $type ) );
        $attributeParametersNode->appendChild( $typeNode );
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $type = $attributeParametersNode->getElementsByTagName( 'type' )->item( 0 )->textContent;
        $classAttribute->setAttribute( self::TYPE_FIELD, $type );
    }

    function diff( $old, $new, $options = false )
    {
        return null;
    }
}

eZDataType::register( eZPackageType::DATA_TYPE_STRING, 'eZPackageType' );

?>
