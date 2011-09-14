<?php
//
// Definition of eZi18nOperator class
//
// Created on: <15-Aug-2006 12:15:07 vd>
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

//!! eZKernel
//! The class eZAlphabetOperator does
/*!

*/


class eZAlphabetOperator
{
    function eZAlphabetOperator( $alphabet = 'alphabet' )
    {
        $this->Operators = array( $alphabet );
        $this->Alphabet = $alphabet;
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$value, $namedParameters )
    {
        switch ( $operatorName )
        {
            case $this->Alphabet:
            {
                $alphabet = eZAlphabetOperator::fetchAlphabet();
                $value = $alphabet;
            } break;
        }
    }

    /*!
      Static
      Returns alphabet.
    */
    static function fetchAlphabet()
    {
        $contentINI = eZINI::instance( 'content.ini' );

        $alphabetRangeList = $contentINI->hasVariable( 'AlphabeticalFilterSettings', 'AlphabetList' )
                             ? $contentINI->variable( 'AlphabeticalFilterSettings', 'AlphabetList' )
                             : array();

        $alphabetFromArray = $contentINI->hasVariable( 'AlphabeticalFilterSettings', 'ContentFilterList' )
                             ? $contentINI->variable( 'AlphabeticalFilterSettings', 'ContentFilterList' )
                             : array( 'default' );

        // If alphabet list is empty
        if ( count( $alphabetFromArray ) == 0 )
            return false;

        $alphabetRangeList = array_merge( $alphabetRangeList, array( 'default' => '97-122' ) );
        $alphabet = array();
        foreach ( $alphabetFromArray as $alphabetFrom )
        {
            // If $alphabetFrom exists in range array $alphabetRangeList
            if ( isset( $alphabetRangeList[$alphabetFrom] ) )
            {
                $lettersArray = explode( ',', $alphabetRangeList[$alphabetFrom] );
                foreach ( $lettersArray as $letter )
                {
                    $rangeArray =  explode( '-', $letter );
                    if ( isset( $rangeArray[1] ) )
                    {
                        $alphabet = array_merge( $alphabet, range( trim( $rangeArray[0] ), trim( $rangeArray[1] ) ) );
                    }
                    else
                        $alphabet = array_merge( $alphabet, array( trim( $letter ) ) );
                }
            }
        }
        // Get alphabet by default (eng-GB)
        if ( count( $alphabet ) == 0 )
        {
            $rangeArray = explode( '-', $alphabetRangeList['default'] );
            $alphabet = range( $rangeArray[0], $rangeArray[1] );
        }
        $resAlphabet = array();
        $i18nINI = eZINI::instance( 'i18n.ini' );
        $charset = $i18nINI->variable( 'CharacterSettings', 'Charset' );

        $codec = eZTextCodec::instance( 'utf-8', $charset );

        $utf8_codec = eZUTF8Codec::instance();
        // Convert all letters of alphabet from unicode to utf-8 and from utf-8 to current locale
        foreach ( $alphabet as $item )
        {
            $utf8Letter = $utf8_codec->toUtf8( $item );
            $resAlphabet[] = $codec ? $codec->convertString( $utf8Letter ) : $utf8Letter;
        }

        return $resAlphabet;
    }

    /// \privatesection
    public $Operators;
    public $Alphabet;
}

?>
