<?php
//
// Created on: <12-Jun-2002 16:25:40 bf>
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
  \class eZContentObjectTranslation ezcontentobjecttranslation.php
  \brief eZContentObjectTranslation handles translation a translation of content objects
  \ingroup eZKernel

  \sa eZContentObject eZContentObjectVersion eZContentObjectTranslation
*/

class eZContentObjectTranslation
{
    function eZContentObjectTranslation( $contentObjectID, $version, $languageCode )
    {
        $this->ContentObjectID = $contentObjectID;
        $this->Version = $version;
        $this->LanguageCode = $languageCode;
        $this->Locale = null;
    }

    function languageCode()
    {
        return $this->LanguageCode;
    }

    function attributes()
    {
        return array( 'contentobject_id',
                      'version',
                      'language_code',
                      'locale' );
    }

    function hasAttribute( $attribute )
    {
        return in_array( $attribute, $this->attributes() );
    }

    function attribute( $attribute )
    {
        if ( $attribute == 'contentobject_id' )
            return $this->ContentObjectID;
        else if ( $attribute == 'version' )
            return $this->Version;
        else if ( $attribute == 'language_code' )
            return $this->LanguageCode;
        else if ( $attribute == 'locale' )
            return $this->locale();
        else
        {
            eZDebug::writeError( "Attribute '$attribute' does not exist", __METHOD__ );
            return null;
        }
    }

    function locale()
    {
        if ( $this->Locale !== null )
            return $this->Locale;
        $this->Locale = eZLocale::instance( $this->LanguageCode );
        return $this->Locale;
    }

    /*!
     Returns the attributes for the current content object translation.
    */
    function objectAttributes( $asObject = true )
    {
        return eZContentObjectVersion::fetchAttributes( $this->Version, $this->ContentObjectID, $this->LanguageCode, $asObject );
    }

    /// The content object identifier
    public $ContentObjectID;
    /// Contains the content object
    public $Version;

    /// Contains the language code for the current translation
    public $LanguageCode;
}
?>
