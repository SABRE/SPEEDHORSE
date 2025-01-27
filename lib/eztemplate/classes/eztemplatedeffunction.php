<?php
//
// Definition of eZTemplateDefFunction class
//
// Created on: <28-Feb-2005 16:03:02 vs>
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
  \class eZTemplateDefFunction eztemplatedeffunction.php
  \ingroup eZTemplateFunctions
  \brief Allows to define/undefine template variables in any place.

  This class allows to execute on of two or more code pieces depending
  on a condition.

  Syntax:
\code
    {def $var1=<value1> [$var2=<value2> ...]}
\endcode

  Example:
\code
    {def $i=10 $j=20}
    {def $s1='hello' $s2='world'}
    ...
    {set $i=$i+1}
    ...
    {undef $i}
    {undef $s1 $s2}
    {undef}
\endcode
*/

class eZTemplateDefFunction
{
    const DEF_FUNCTION_NAME = 'def';
    const UNDEF_FUNCTION_NAME = 'undef';

    /*!
     * Returns an array of the function names, required for eZTemplate::registerFunctions.
     */
    function &functionList()
    {
        $functionList = array( eZTemplateDefFunction::DEF_FUNCTION_NAME, eZTemplateDefFunction::UNDEF_FUNCTION_NAME );
        return $functionList;
    }

    /*!
     * Returns the attribute list which is 'delimiter', 'elseif' and 'else'.
     * key:   parameter name
     * value: can have children
     */
    function attributeList()
    {
        return array();
    }


    /*!
     * Returns the array with hits for the template compiler.
     */
    function functionTemplateHints()
    {
        return array( eZTemplateDefFunction::DEF_FUNCTION_NAME   => array( 'parameters' => true,
                                                                'static' => false,
                                                                'transform-parameters' => true,
                                                                'tree-transformation' => true ),
                      eZTemplateDefFunction::UNDEF_FUNCTION_NAME => array( 'parameters' => true,
                                                                'static' => false,
                                                                'transform-parameters' => true,
                                                                'tree-transformation' => true ) );
    }

    /*!
     * Compiles the function into PHP code.
     */
    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, &$parameters, $privateData )
    {
        $undef = ( $functionName == 'undef' );
        $newNodes = array();

        if ( !$parameters )
        {
            if ( !$undef )
                // prevent execution of the function in processed mode
                return array( eZTemplateNodeTool::createCodePieceNode( "// an error occured in $functionName" ) );

            // {undef} called w/o arguments => destroy all local variables
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// undef all" );
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "\$tpl->unsetLocalVariables();" );
            return $newNodes;
        }

        $nodePlacement = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
        foreach ( array_keys( $parameters ) as $parameterName )
        {
            $parameterData = $parameters[$parameterName];
            if ( $undef )
            {
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// undef \$$parameterName" );
                // generates "$tpl->unsetLocalVariable();"
                $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( array( $namespaceValue = false,
                                                                                  $scope = eZTemplate::NAMESPACE_SCOPE_LOCAL,
                                                                                  $parameterName ),
                                                                           array( 'remember_set' => false, 'local-variable' => true ) );
            }
            else
            {
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "// def \$$parameterName" );
                // generates "$tpl->setLocalVariable();"
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameterData, $nodePlacement, array( 'local-variable' => true ),
                                                                      array( $namespaceValue = false, $scope = eZTemplate::NAMESPACE_SCOPE_LOCAL, $parameterName ),
                                                                      $onlyExisting = false, $overwrite = true, false, $rememberSet = false );
            }
        }

        return $newNodes;
    }

    /*!
     * Actually executes the function (in processed mode).
     */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $undef = ( $functionName == eZTemplateDefFunction::UNDEF_FUNCTION_NAME ) ? true : false;

        if ( $undef && !count( $functionParameters ) ) // if {undef} called w/o arguments
        {
            // destroy all variables defined in the current template using {def}
            $tpl->unsetLocalVariables();
        }

        foreach ( array_keys( $functionParameters ) as $key )
        {
            $varName  = $key;
            $param    = $functionParameters[$varName];
            $varValue = $tpl->elementValue( $param, $rootNamespace, $currentNamespace, $functionPlacement );


            if ( $undef ) // {undef}
            {
                if ( !$tpl->hasLocalVariable( $varName, $rootNamespace ) )
                    $tpl->warning( eZTemplateDefFunction::UNDEF_FUNCTION_NAME, "Variable '$varName' is not defined with {def}.", $functionPlacement );
                else
                    $tpl->unsetLocalVariable( $varName, $rootNamespace );

            }
            else // {def}
            {
                if ( $tpl->hasVariable( $varName, $rootNamespace ) ) // if the variable already exists
                {
                    // we don't create new variable but just assign value to the existing one.
                    $tpl->warning( eZTemplateDefFunction::DEF_FUNCTION_NAME, "Variable '$varName' is already defined.", $functionPlacement );
                    $tpl->setVariable( $varName, $varValue, $rootNamespace );
                }
                else
                    // create a new local variable and assign a value to it.
                    $tpl->setLocalVariable( $varName, $varValue, $rootNamespace );

            }
        }
    }

    /*!
     * Returns false, telling the template parser that the function cannot have children.
     */
    function hasChildren()
    {
        return false;
    }
}

?>
