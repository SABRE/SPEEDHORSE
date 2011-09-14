<?php
//
// Definition of eZPDFXMLOutput class
//
// Created on: <25-Sep-2006 15:05:00 ks>
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


class eZPDFXMLOutput extends eZXHTMLXMLOutput
{

    function eZPDFXMLOutput( &$xmlData, $aliasedType, $contentObjectAttribute = null )
    {
        $this->eZXHTMLXMLOutput( $xmlData, $aliasedType, $contentObjectAttribute );

        $this->OutputTags['table']['initHandler'] = 'initHandlerTable';
        $this->OutputTags['li']['initHandler'] = 'initHandlerLi';
    }

    function initHandlerTable( $element, &$attributes, &$sibilingParams, &$parentParams )
    {
        $ret = array();

        if( !isset( $attributes['width'] ) )
            $attributes['width'] = '100%';

        if( !isset( $attributes['border'] ) )
            $attributes['border'] = 1;

        return $ret;
    }

    function initHandlerLi( $element, &$attributes, &$sibilingParams, &$parentParams )
    {
        if( !isset( $sibilingParams['list_count'] ) )
            $sibilingParams['list_count'] = 1;
        else
            $sibilingParams['list_count']++;

        $ret = array( 'tpl_vars' => array( 'list_count' => $sibilingParams['list_count'],
                                           'tag_name' => $element->parentNode->nodeName ) );
        return $ret;
    }

    public $TemplatesPath = 'design:content/datatype/pdf/ezxmltags/';
}

?>
