<?php
//
// Definition of Translations class
//
// Created on: <23-���-2003 12:52:42 sp>
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


$tpl = eZTemplate::factory();
$http = eZHTTPTool::instance();
$Module = $Params['Module'];

$tpl->setVariable( 'module', $Module );


if ( $Module->isCurrentAction( 'New' ) /*or
     $Module->isCurrentAction( 'Edit' )*/ )
{
    $tpl->setVariable( 'is_edit', $Module->isCurrentAction( 'Edit' ) );
    $Result['content'] = $tpl->fetch( 'design:content/translationnew.tpl' );
    $Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Translation' ),
                                    'url' => false ),
                             array( 'text' => 'New',
                                    'url' => false ) );
    return;
}

if ( $Module->isCurrentAction( 'StoreNew' ) /* || $http->hasPostVariable( 'StoreButton' ) */ )
{
    $localeID = $Module->actionParameter( 'LocaleID' );
    $translationName = '';
    $translationLocale = '';
    eZDebug::writeDebug( $localeID, 'localeID' );
    if ( $localeID != '' and
         $localeID != -1 )
    {
        $translationLocale = $localeID;
        $localeInstance = eZLocale::instance( $translationLocale );
        $translationName = $localeInstance->internationalLanguageName();
    }
    else
    {
        $translationName = $Module->actionParameter( 'TranslationName' );
        $translationLocale = $Module->actionParameter( 'TranslationLocale' );
        eZDebug::writeDebug( $translationName, 'translationName' );
        eZDebug::writeDebug( $translationLocale, 'translationLocale' );
    }

    // Make sure the locale string is valid, if not we try to extract a valid part of it
    if ( !preg_match( "/^" . eZLocale::localeRegexp( false, false ) . "$/", $translationLocale ) )
    {
        if ( preg_match( "/(" . eZLocale::localeRegexp( false, false ) . ")/", $translationLocale, $matches ) )
        {
            $translationLocale = $matches[1];
        }
        else
        {
            // The locale cannot be used so we show the edit page again.
            $tpl->setVariable( 'is_edit', $Module->isCurrentAction( 'Edit' ) );
            $Result['content'] = $tpl->fetch( 'design:content/translationnew.tpl' );
            $Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Translation' ),
                                            'url' => false ),
                                     array( 'text' => 'New',
                                            'url' => false ) );
            return;
        }
    }

    if ( !eZContentLanguage::fetchByLocale( $translationLocale ) )
    {
        $locale = eZLocale::instance( $translationLocale );
        if ( $locale->isValid() )
        {
            $translation = eZContentLanguage::addLanguage( $locale->localeCode(), $translationName );
        }
        else
        {
            // The locale cannot be used so we show the edit page again.
            $tpl->setVariable( 'is_edit', $Module->isCurrentAction( 'Edit' ) );
            $Result['content'] = $tpl->fetch( 'design:content/translationnew.tpl' );
            $Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Translation' ),
                                            'url' => false ),
                                     array( 'text' => 'New',
                                            'url' => false ) );
            return;
        }
    }
}

if ( $Module->isCurrentAction( 'Remove' ) )
{
    $seletedIDList = $Module->actionParameter( 'SelectedTranslationList' );

    $db = eZDB::instance();

    $db->begin();
    foreach ( $seletedIDList as $translationID )
    {
        eZContentLanguage::removeLanguage( $translationID );
    }
    $db->commit();
}


if ( $Params['TranslationID'] )
{
    $translation = eZContentLanguage::fetch( $Params['TranslationID'] );

    if( !$translation )
    {
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

    $tpl->setVariable( 'translation',  $translation );

    $Result['content'] = $tpl->fetch( 'design:content/translationview.tpl' );
    $Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Content translations' ),
                                    'url' => 'content/translations' ),
                             array( 'text' => $translation->attribute( 'name' ),
                                    'url' => false ) );
    return;
}

$availableTranslations = eZContentLanguage::fetchList();

$tpl->setVariable( 'available_translations', $availableTranslations );

$Result['content'] = $tpl->fetch( 'design:content/translations.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Languages' ),
                                'url' => false ) );

?>
