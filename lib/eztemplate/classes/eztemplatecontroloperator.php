<?php
//
// Definition of eZTemplateControlOperator class
//
// Created on: <18-Apr-2002 12:15:07 amos>
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
  \class eZTemplateControlOperator eztemplatetypeoperator.php
  \ingroup eZTemplateOperators
  \brief Operators for checking variable type

Usage:
// Evalue condition and if true return body
cond(is_set($var),$var,
     true(),2)
// Return first element that is set
first_set($var1,$var2,$var3,0)

*/

class eZTemplateControlOperator
{
    /*!
     Initializes the operator class with the various operator names.
    */
    function eZTemplateControlOperator(  /*! The name array */
        $condName = 'cond',
        $firstSetName = 'first_set' )
    {
        $this->Operators = array( $condName, $firstSetName );
        $this->CondName = $condName;
        $this->FirstSetName = $firstSetName;
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    function operatorTemplateHints()
    {
        return array( $this->CondName => array( 'input' => false,
                                                'output' => true,
                                                'parameters' => true,
                                                'element-transformation' => true,
                                                'transform-parameters' => true,
                                                'input-as-parameter' => false,
                                                'element-transformation-func' => 'condTransform' ),
                      $this->FirstSetName => array( 'input' => false,
                                                    'output' => true,
                                                    'parameters' => true,
                                                    'element-transformation' => true,
                                                    'transform-parameters' => true,
                                                    'input-as-parameter' => false,
                                                    'element-transformation-func' => 'condTransform' ) );
    }

    function condTransform( $operatorName, &$node, $tpl, &$resourceData,
                            $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        switch( $operatorName )
        {
            case $this->CondName:
            {
                $paramCount = count( $parameters );
                $clauseCount = floor( $paramCount / 2 );
                $hasDefaultClause = ( $paramCount % 2 ) != 0;

                if ( $paramCount == 1 )
                {
                    return $parameters[0];
                }

                $values = array();
                $code = '';
                $spacing = 0;
                $spacingCode = '';
                for ( $i = 0; $i < $clauseCount; ++$i )
                {
                    $prevSpacingCode = $spacingCode;
                    $spacingCode = str_repeat( " ", $spacing*4 );
                    if ( $i > 0 )
                    {
                        $code .= $prevSpacingCode . "else\n" . $prevSpacingCode . "{\n";
                    }

                    $values[] = $parameters[$i*2];
                    if ( $i > 0 )
                    {
                        $code .= $spacingCode . "%code" . count( $values ) . "%\n";
                    }
                    $code .= $spacingCode. 'if ( %' . count( $values ) . "% )\n" . $spacingCode . "{\n";

                    if ( !eZTemplateNodeTool::isStaticElement( $parameters[$i*2 + 1] ) )
                    {
                        $values[] = $parameters[$i*2 + 1];
                        $code .= ( $spacingCode . "    %code" . count( $values ) . "%\n" .
                                   $spacingCode . "    %output% = %" . count( $values ) . "%;\n" );
                    }
                    else
                    {
                        $code .= $spacingCode . '    %output% = ' . eZPHPCreator::variableText( eZTemplateNodeTool::elementStaticValue( $parameters[$i*2 + 1] ), 0, 0, false ) . ';' . "\n";
                    }
                    $code .= $spacingCode . "}\n";
                    ++$spacing;
                }
                $bracketCount = $clauseCount - 1;
                if ( $hasDefaultClause )
                {
                    ++$bracketCount;
                    $values[] = $parameters[$paramCount - 1];
                    if ( $clauseCount > 0 )
                    {
                        $code .= $spacingCode . "else\n" . $spacingCode . "{\n" . $spacingCode . "    %code" . count( $values ) . "%\n    ";
                    }

                    $code .= $spacingCode . '%output% = %' . count( $values ) . "%;\n";
                }
                for ( $clauseIndex = 0; $clauseIndex < $bracketCount; ++$clauseIndex )
                {
                    $spacingCode = str_repeat( " ", ( $bracketCount - $clauseIndex - 1 ) *4 );
                    $code .= $spacingCode . "}\n";
                }

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;

            case $this->FirstSetName:
            {
                $values = array();
                $code = '';
                $spacing = 0;
                $spacingCode = '';
                $nestCount = 0;
                for( $i = 0; $i < count( $parameters ); ++$i )
                {
                    if ( $i != 0 )
                    {
                        $code .= "$spacingCode}\n" . $spacingCode . "else\n$spacingCode{\n";
                    }
                    $spacingCode = str_repeat( ' ', $spacing*4 );
                    ++$spacing;

                    if ( eZTemplateNodeTool::isStaticElement( $parameters[$i] ) )
                    {
                        $code .= "$spacingCode%output% = " . eZPHPCreator::variableText( eZTemplateNodeTool::elementStaticValue( $parameters[$i] ), 0, 0, false ) . ";\n";
                        break;
                    }
                    ++$nestCount;

                    $values[] = $parameters[$i];
                    $code .= ( $spacingCode . "%code" . count( $values ) . "%\n" .
                               $spacingCode . 'if ( isset( %' . count( $values ) . "% ) )\n" .
                               $spacingCode . "{\n" .
                               $spacingCode . "    %output% = %" . count( $values ) . '%;' . "\n" );
                }
                for ( $i = 0; $i < $nestCount; ++$i )
                {
                    $spacing = $nestCount - $i - 1;
                    $spacingCode = str_repeat( ' ', $spacing*4 );
                    $code .= $spacingCode . "}\n";
                }

                return array( eZTemplateNodeTool::createCodePieceElement( $code, $values ) );
            } break;
        }
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
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
        return array();
    }

    /*!
     Examines the input value and outputs a boolean value. See class documentation for more information.
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$value, $namedParameters,
                     $placement )
    {
        switch ( $operatorName )
        {
            case $this->CondName:
            {
                $parameterCount = count( $operatorParameters );
                $clauseCount = floor( $parameterCount / 2 );
                $clauseMod = $parameterCount % 2;
                $conditionSuccess = false;
                for ( $i = 0; $i < $clauseCount; ++$i )
                {
                    $condition = $tpl->elementValue( $operatorParameters[$i*2], $rootNamespace, $currentNamespace, $placement );
                    if ( $condition )
                    {
                        $body = $tpl->elementValue( $operatorParameters[$i*2 + 1], $rootNamespace, $currentNamespace, $placement );
                        $conditionSuccess = true;
                        $value = $body;
                        break;
                    }
                }
                if ( !$conditionSuccess and
                     $clauseMod > 0 )
                {
                    $condition = $tpl->elementValue( $operatorParameters[count($operatorParameters) - 1], $rootNamespace, $currentNamespace, $placement );
                    if ( $condition )
                    {
                        $conditionSuccess = true;
                        $value = $condition;
                    }
                }
            } break;
            case $this->FirstSetName:
            {
                if ( count( $operatorParameters ) > 0 )
                {
                    for ( $i = 0; $i < count( $operatorParameters ); ++$i )
                    {
                        $operand = $tpl->elementValue( $operatorParameters[$i], $rootNamespace, $currentNamespace, $placement, true );
                        if ( $operand !== null )
                        {
                            $value = $operand;
                            return;
                        }
                    }
                }
                $value = null;
            } break;
        }
    }

    /// The array of operators
    public $Operators;
}

?>
