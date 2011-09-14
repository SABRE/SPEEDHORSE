<?php
//
// Definition of eZTemplatePHPOperator class
//
// Created on: <01-Mar-2002 13:50:09 amos>
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
  \class eZTemplatePHPOperator eztemplatephpoperator.php
  \ingroup eZTemplateOperators
  \brief Makes it easy to add php functions as operators

  This class makes it easy to add existing PHP functions as template operators.
  It maps a template operator to a PHP function, the function must take one
  parameter and return the result.
  The redirection is done by supplying an associative array to the class,
  each key is the operatorname and the value is the PHP function name.

  Example:
\code
$tpl->registerOperators( new eZTemplatePHPOperator( array( "upcase" => "strtoupper",
                                                           "reverse" => "strrev" ) ) );
\endcode
*/

class eZTemplatePHPOperator
{
    /*!
     Initializes the object with the redirection array.
    */
    function eZTemplatePHPOperator( $php_names )
    {
        if ( !is_array( $php_names ) )
            $php_names = array( $php_names );
        $this->PHPNames = $php_names;
        reset( $php_names );
        while ( list( $key, $val ) = each( $php_names ) )
        {
            $this->Operators[] = $key;
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
        $hints = array();
        foreach ( array_keys( $this->PHPNames ) as $name )
        {
            $hints[$name] = array( 'input' => true,
                                   'output' => true,
                                   'parameters' => false,
                                   'element-transformation' => true,
                                   'transform-parameters' => true,
                                   'input-as-parameter' => true,
                                   'element-transformation-func' => 'phpOperatorTransformation');
        }
        return $hints;
    }

    function phpOperatorTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                        $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $values = array();
        $function = $operatorName;

        if ( !isset( $parameters[0] ) )
        {
            return false;
        }
        $newElements = array();
        $phpname = $this->PHPNames[$operatorName];

        $values[] = $parameters[0];
        $code = "%output% = $phpname( %1% );\n";

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values );
        return $newElements;
    }

    /*!
     Executes the PHP function for the operator $op_name.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$value, $namedParameters, $placement )
    {
        $phpname = $this->PHPNames[$operatorName];
        if ( $value !== null )
            $operand = $value;
        else
            $operand = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace, $placement );
        $value = $phpname( $operand );
    }

    /// The array of operators, used for registering operators
    public $Operators;
    /// The associative array of operator/php function redirection
    public $PHPNames;
}

?>
