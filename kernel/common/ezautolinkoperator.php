<?php
//
// Definition of eZAutoLinkOperator class
//
// Created on: <07-Feb-2003 09:39:55 bf>
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

class eZAutoLinkOperator
{
    function eZAutoLinkOperator( $name = 'autolink' )
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
        return array( 'max_chars' => array( 'type' => 'integer',
                                            'required' => false,
                                            'default' => null ) );
    }

    function formatUri( $url, $max )
    {
        $text = $url;
        if (strlen($text) > $max)
        {
            $text = substr($text, 0, ($max / 2) - 3). '...'. substr($text, strlen($text) - ($max / 2));
        }
        return "<a href=\"$url\" title=\"$url\">$text</a>";
    }

    /*!
     \static
    */
    function addURILinks( $text, $max, $methods = 'http|https|ftp' )
    {
        return preg_replace(
            "!($methods):\/\/[\w]+(.[\w]+)([\w\-\.,@?^=%&:\/~\+#;*\(\)\!]*[\w\-\@?^=%&\/~\+#;*\(\)\!])?!e",
            'eZAutoLinkOperator::formatUri("$0", '. $max. ')',
            $text
        );
    }


    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $ini = $tpl->ini();
        $max = $ini->variable( 'AutoLinkOperator', 'MaxCharacters' );
        if ( $namedParameters['max_chars'] !== null )
        {
            $max = $namedParameters['max_chars'];
        }

        $methods = $ini->variable( 'AutoLinkOperator', 'Methods' );
        $methodText = implode( '|', $methods );

        // Replace mail
        $operatorValue = preg_replace( "#(([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+@([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+)#", "<a href='mailto:\\1'>\\1</a>", $operatorValue );

        // Replace http/ftp etc. links
        $operatorValue = eZAutoLinkOperator::addURILinks($operatorValue, $max, $methodText);
    }

    /// \privatesection
    public $Operators;
}

?>
