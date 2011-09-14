<?php
//
// Created on: <15-Feb-2010 17:37:54 ar>
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


class ezpI18n
{
    /**
     * Replaces keys found in \a $text with values in \a $arguments.
     * If \a $arguments is an associative array it will use the argument
     * keys as replacement keys. If not it will convert the index to
     * a key looking like %n, where n is a number between 1 and 9.
     *
     * @param string $string
     * @param array $arguments
     * @return string
    */
    protected static function insertArguments( $text, $arguments )
    {
        if ( is_array( $arguments ) )
        {
            $replaceList = array();
            foreach ( $arguments as $argumentKey => $argumentItem )
            {
                if ( is_int( $argumentKey ) )
                    $replaceList['%' . ( ($argumentKey%9) + 1 )] = $argumentItem;
                else
                    $replaceList[$argumentKey] = $argumentItem;
            }
            $text = strtr( $text, $replaceList );
        }
        return $text;
    }

    /**
     * Enabled if the site.ini settings RegionalSettings/TextTranslation is set to disabled
     *
     * @return bool
    */
    protected static function isEnabled()
    {
        static $isEnabled = null;
        if ( $isEnabled === null )
        {
            $ini = eZINI::instance();
            $useTextTranslation = $ini->variable( 'RegionalSettings', 'TextTranslation' ) != 'disabled';
            $isEnabled = $useTextTranslation || eZTranslatorManager::dynamicTranslationsEnabled();
        }
        return $isEnabled;
    }

    /**
     * Translates the source \a $source with context \a $context and optional comment \a $comment
     * and returns the translation if translations are enabled.
     * Uses {@link ezpI18n::translateText()}
     *
     * Example:
     * translate( 'content/view', 'There are %count nodes in this list out of %total total nodes.', 'Children view of nodes for whole site', array( '%count' => $c, '%total' => $t ) );
     *
     * @param string $context
     * @param string $source
     * @param string|null $comment
     * @param array|null $arguments
     * @return string
     */
    public static function tr( $context, $source, $comment = null, $arguments = null )
    {
        if ( self::isEnabled() )
        {
            return self::translateText( $context, $source, $comment, $arguments );
        }
        return self::insertArguments( $source, $arguments );
    }

    /**
     * Translates the source \a $source with context \a $context and optional comment \a $comment
     * and returns the translation if locale code is not eng-GB.
     * Uses {@link eZTranslatorMananger::translate()} to do the actual translation
     *
     * Example:
     * translateText( 'content/view', 'There are %count nodes in this list out of %total total nodes.', 'Children view of nodes for whole site', array( '%count' => $c, '%total' => $t ) );
     *
     * @param string $context
     * @param string $source
     * @param string|null $comment
     * @param array|null $arguments
     * @return string
     */
    protected static function translateText( $context, $source, $comment = null, $arguments = null )
    {
        $localeCode = eZLocale::instance()->localeFullCode();
        if ( $localeCode == 'eng-GB' )
        {
            // we don't have ts-file for 'eng-GB'.
            return self::insertArguments( $source, $arguments );
        }

        $ini = eZINI::instance();
        $useCache = $ini->variable( 'RegionalSettings', 'TranslationCache' ) != 'disabled';
        eZTSTranslator::initialize( $context, $localeCode, 'translation.ts', $useCache );

        // Bork translation: Makes it easy to see what is not translated.
        // If no translation is found in the eZTSTranslator, a Bork translation will be returned.
        // Bork is different than, but similar to, eng-GB, and is enclosed in square brackets [].
        $developmentMode = $ini->variable( 'RegionalSettings', 'DevelopmentMode' ) != 'disabled';
        if ( $developmentMode )
        {
            eZBorkTranslator::initialize();
        }

        $man = eZTranslatorManager::instance();
        $trans = $man->translate( $context, $source, $comment );
        if ( $trans !== null ) {
            return self::insertArguments( $trans, $arguments );
        }

        if ( $comment != null and strlen( $comment ) > 0 )
            eZDebug::writeDebug( "Missing translation for message in context: '$context' with comment: '$comment'. The untranslated message is: '$source'", __METHOD__ );
        else
            eZDebug::writeDebug( "Missing translation for message in context: '$context'. The untranslated message is: '$source'", __METHOD__ );

        return self::insertArguments( $source, $arguments );
    }
}

?>
