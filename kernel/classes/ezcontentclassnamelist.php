<?php
//
// Created on: <02-Oct-2006 13:37:23 dl>
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

class eZContentClassNameList extends eZSerializedObjectNameList
{
    function eZContentClassNameList( $serializedNameList = false )
    {
        eZSerializedObjectNameList::eZSerializedObjectNameList( $serializedNameList );
    }

    function create( $serializedNamesString = false )
    {
        $object = new eZContentClassNameList( $serializedNamesString );
        return $object;
    }

    function store( $contentClass )
    {
        if ( $this->hasDirtyData() && is_object($contentClass ) )
        {
            $classID = $contentClass->attribute( 'id' );
            $classVersion = $contentClass->attribute( 'version' );
            $languages = $contentClass->attribute( 'languages' );
            $initialLanguageID = $contentClass->attribute( 'initial_language_id' );

            // update existing
            $contentClassNames = eZContentClassName::fetchList( $classID, $classVersion, array_keys( $languages ) );
            foreach ( $contentClassNames as $className )
            {
                $languageLocale = $className->attribute( 'language_locale' );
                $className->setAttribute( 'name', $this->nameByLanguageLocale( $languageLocale ) );
                if ( $initialLanguageID == $className->attribute( 'language_id' ) )
                    $className->setAttribute( 'language_id', $initialLanguageID | 1 );

                $className->sync(); // avoid unnecessary sql-updates if nothing changed

                unset( $languages[$languageLocale] );
            }

            // create new
            if ( count( $languages ) > 0 )
            {
                foreach ( $languages as $languageLocale => $language )
                {
                    if ( !$language instanceof eZContentLanguage )
                    {
                        eZDebug::writeError( $languageLocale . ' is not a instance of eZContentLanguage', __METHOD__ );
                        continue;
                    }
                    $languageID = $language->attribute( 'id' );
                    if ( $initialLanguageID == $languageID )
                        $languageID = $initialLanguageID | 1;

                    $className = new eZContentClassName( array( 'contentclass_id' => $classID,
                                                                'contentclass_version' => $classVersion,
                                                                'language_locale' => $languageLocale,
                                                                'language_id' => $languageID,
                                                                'name' => $this->nameByLanguageLocale( $languageLocale ) ) );
                    $className->store();
                }
            }

            $this->setHasDirtyData( false );
        }
    }

    static function remove( $contentClass )
    {
        eZContentClassName::removeClassName( $contentClass->attribute( 'id' ), $contentClass->attribute( 'version' ) );
    }
}

?>
