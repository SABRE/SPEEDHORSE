<?php
//
// Definition of eZTOCOperator class
//
// Created on: <24-Aug-2005 15:11:12 bf>
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
  \class eZTOCOperator eztocoperator.php
  \brief The class eZTOCOperator does

*/

class eZTOCOperator
{
    /*!
     Constructor
    */
    function eZTOCOperator()
    {
    }

    /*!
     \return an array with the template operator name.
    */
    function operatorList()
    {
        return array( 'eztoc' );
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type,
             this is needed for operator classes that have multiple operators.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     See eZTemplateOperator::namedParameterList
    */
    function namedParameterList()
    {
        return array( 'eztoc' => array( 'dom' => array( 'type' => 'object',
                                                        'required' => true,
                                                        'default' => 0 ) ) );
    }
    /*!
     Executes the PHP function for the operator cleanup and modifies \a $operatorValue.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $dom = $namedParameters['dom'];
        if ( $dom instanceof eZContentObjectAttribute )
        {
            $this->ObjectAttributeId = $dom->attribute( 'id' );
            $content = $dom->attribute( 'content' );
            $xmlData = $content->attribute( 'xml_data' );

            $domTree = new DOMDocument( '1.0', 'utf-8' );
            $domTree->preserveWhiteSpace = false;
            $success = $domTree->loadXML( $xmlData );

            $tocText = '';
            if ( $success )
            {
                $this->HeaderCounter = array();
                $this->LastHeaderLevel = 0;

                $rootNode = $domTree->documentElement;
                $tocText .= $this->handleSection( $rootNode );

                while ( $this->LastHeaderLevel > 0 )
                {
                    $tocText .= "</li>\n</ul>\n";
                    $this->LastHeaderLevel--;
                }
            }
        }
        $operatorValue = $tocText;
    }

    function handleSection( $sectionNode, $level = 0 )
    {
        // Reset next level counter
        if ( !isset( $this->HeaderCounter[$level + 1] ) )
        {
            $this->HeaderCounter[$level + 1] = 0;
        }

        $tocText = '';
        $children = $sectionNode->childNodes;
        foreach ( $children as $child )
        {
            if ( $child->nodeName == 'section' )
            {
                $tocText .= $this->handleSection( $child, $level + 1 );
            }

            if ( $child->nodeName == 'header' )
            {
                if ( $level > $this->LastHeaderLevel )
                {
                    while ( $level > $this->LastHeaderLevel )
                    {
                        $tocText .= "\n<ul><li>";
                        $this->LastHeaderLevel++;
                    }
                }
                elseif ( $level == $this->LastHeaderLevel )
                {
                    $tocText .= "</li>\n<li>";
                }
                else
                {
                    $tocText .= "</li>\n";
                    while ( $level < $this->LastHeaderLevel )
                    {
                        $tocText .= "</ul></li>\n";
                        $this->LastHeaderLevel--;
                    }
                    $tocText .= "<li>";
                }
                $this->LastHeaderLevel = $level;

                $this->HeaderCounter[$level] += 1;
                $i = 1;
                $headerAutoName = "";
                while ( $i <= $level )
                {
                    if ( $i > 1 )
                        $headerAutoName .= "_";

                    $headerAutoName .= $this->HeaderCounter[$i];
                    $i++;
                }
                $tocText .= '<a href="#eztoc' . $this->ObjectAttributeId . '_' . $headerAutoName . '">' . $child->textContent . '</a>';
            }
        }

        return $tocText;
    }

    public $HeaderCounter = array();
    public $LastHeaderLevel = 0;

    public $ObjectAttributeId;
}

?>
