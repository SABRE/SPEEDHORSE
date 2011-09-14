<?php
//
// Definition of eZURLAliasFilter class
//
// Created on: <22-Jun-2007 09:03:31 amos>
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

/**
 * The eZURLAliasFilter class the interface for all url alias filters,
 * the filter implementation must implement the process method.
 *
 * For execution of the configured filters use the static method processFilters.
 * Help with configuration is found in settings/site.ini under the group URLTranslator.
 *
 * @abstract
 */

abstract class eZURLAliasFilter
{
    /**
     * Initialize the filter object.
     */
    public function eZURLAliasFilter()
    {
    }

    /*
     *
     * Process the url alias element $text and return the new element as a string.
     * This method must be overriden in custom URL alias filters.
     *
     * This function has not been declared as "abstract" because of backward compatibility
     * but you should see it as an abstract method.
     *
     * @abstract
     * @param string $text           The URL alias
     * @param string $languageObject The current language object used for the string $text.
     * @param object $caller         The object which called the filtering process, can be null.
     * @return string the processed URL alias
     */
    public function process( $text, &$languageObject, &$caller )
    {
        return $text;
    }

    /**
     *
     * Process all configured filters and return the resulting text.
     *
     * Filters are found in the INI group URLTranslator and the setting Filters.
     * This is done in combination with the setting Extensions which controls
     * which extensions have filter classes.
     *
     * The parameters $text, $languageObject and $caller are sent to the method
     * process on the filter object.
     *
     * Note: The filter list will be cached in memory to improve performance of subsequent calls.
     *
     * @static
     * @param string $text           The URL alias
     * @param string $languageObject The current language object used for the string $text.
     * @param object $caller         The object which called the filtering process, can be null.
     * @return string the URL alias processed by the process() method
     */
    public static function processFilters( $text, $languageObject, $caller )
    {
        $ini = eZINI::instance( 'site.ini' );
        $filterClassList = $ini->variable( 'URLTranslator', 'FilterClasses' );

        foreach ( $filterClassList as $filterClass )
        {
            if( !class_exists( $filterClass ) )
            {
                eZDebug::writeError( $filterClass . ' does not exist, please run bin/php/ezpgenerateautoload.php', __METHOD__ );
                continue;
            }

            $filter = new $filterClass();
            $text = $filter->process( $text, $languageObject, $caller );
        }

        return $text;
    }
}

?>
