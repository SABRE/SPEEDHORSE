<?php
//
// Definition of eZTemplateDigestOperator class
//
// Created on: <18-Jul-2003 13:00:18 bh>
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
  \class eZTemplateDigestOperator eztemplatedigestoperator.php
  \ingroup eZTemplateOperators
\code

\endcode

*/

class eZTemplateDigestOperator
{
    /*!
     Constructor.
    */
    function eZTemplateDigestOperator()
    {
        $this->Operators = array( 'crc32', 'md5', 'rot13' );
        if ( function_exists( 'sha1' ) )
        {
            $this->Operators[] = 'sha1';
        }
        foreach ( $this->Operators as $operator )
        {
            $name = $operator . 'Name';
            $name[0] = $name[0] & "\xdf";
            $this->$name = $operator;
        }
    }

    /*!
     Returns the template operators.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    function operatorTemplateHints()
    {
        return array( $this->Crc32Name => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => false,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => 'always',
                                                 'element-transformation-func' => 'hashTransformation'),
                      $this->Md5Name => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => 'always',
                                               'element-transformation-func' => 'hashTransformation'),
                      $this->Sha1Name => array( 'input' => true,
                                                'output' => true,
                                                'parameters' => true,
                                                'element-transformation' => true,
                                                'transform-parameters' => true,
                                                'input-as-parameter' => 'always',
                                                'element-transformation-func' => 'hashTransformation'),
                      $this->Rot13Name => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => true,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => 'always',
                                                 'element-transformation-func' => 'hashTransformation') );
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return false;
    }

    function hashTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                 $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        if ( ( count( $parameters ) != 1) )
        {
            return false;
        }

        $code = '';
        $function = '';
        $newElements = array();
        $values = array( $parameters[0] );

        switch ( $operatorName )
        {
            case 'crc32':
                {
                    $function = "eZSys::ezcrc32";
                } break;

            case 'rot13':
                {
                    $function = 'str_rot13';
                } break;

            default:
                {
                    $function = $operatorName;
                } break;
        }

        $code .= "%output% = $function( %1% );\n";

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    /*!
     Display the variable.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters,
                     $placement )
    {
        $digestData = $operatorValue;
        switch ( $operatorName )
        {
            // Calculate and return crc32 polynomial.
            case $this->Crc32Name:
            {
                $operatorValue = eZSys::ezcrc32( $digestData );
            }break;

            // Calculate the MD5 hash.
            case $this->Md5Name:
            {
                $operatorValue = md5( $digestData );
            }break;

            // Calculate the SHA1 hash.
            case $this->Sha1Name:
            {
                $operatorValue = sha1( $digestData );
            }break;

            // Preform rot13 transform on the string.
            case $this->Rot13Name:
            {
                $operatorValue = str_rot13( $digestData );
            }break;

            // Default case: something went wrong - unknown things...
            default:
            {
                $tpl->warning( $operatorName, "Unknown input type '$type'", $placement );
            } break;
        }
    }

    /// The array of operators, used for registering operators
    public $Operators;
}

?>
