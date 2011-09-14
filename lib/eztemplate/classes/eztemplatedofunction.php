<?php
//
// Definition of eZTemplateDoFunction class
//
// Created on: <25-Feb-2005 13:04:30 vs>
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
  \class eZTemplateDoFunction eztemplatedofunction.php
  \ingroup eZTemplateFunctions
  \brief DO..WHILE loop

  Syntax:
\code
    {do}
        [{delimiter}...{/delimiter}]
        [{break}]
        [{continue}]
        [{skip}]
    {/do while <condition> [sequence <array> as $seqVar]}
\endcode

  Example:
\code
    {do}
        One more beer, please.
    {/do while eq( $drunk, false() )}
\endcode
*/

class eZTemplateDoFunction
{
    const FUNCTION_NAME = 'do';

    /*!
     * Returns an array of the function names, required for eZTemplate::registerFunctions().
     */
    function &functionList()
    {
        $functionList = array( eZTemplateDoFunction::FUNCTION_NAME );
        return $functionList;
    }

    /*!
     * Returns the attribute list
     * key:   parameter name
     * value: can have children
     */
    function attributeList()
    {
        return array( 'delimiter' => true,
                      'break'     => false,
                      'continue'  => false,
                      'skip'      => false );
    }


    /*!
     * Returns the array with hits for the template compiler.
     */
    function functionTemplateHints()
    {
        return array( eZTemplateDoFunction::FUNCTION_NAME => array( 'parameters' => true,
                                                             'static' => false,
                                                             'transform-parameters' => true,
                                                             'tree-transformation' => true ) );
    }

    /*!
     * Compiles the function and its children into PHP code.
     */
    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, $parameters, $privateData )
    {
        // {/do while <condition> [sequence <sequence_array> as $<sequence_var>]}

        $tpl->DoCounter++;
        $newNodes      = array();
        $nodePlacement = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
        $uniqid        = md5( $nodePlacement[2] ) . "_" . $tpl->DoCounter;

        // initialize loop
        $loop = new eZTemplateCompiledLoop( eZTemplateDoFunction::FUNCTION_NAME,
                                            $newNodes, $parameters, $nodePlacement, $uniqid,
                                            $node, $tpl, $privateData );

        $loop->initVars();

        // loop header
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "while ( 1 ) // do..while\n{" );
        $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode();

        $loop->processBody();

        // loop footer
        $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['condition'], $nodePlacement, array( 'treat-value-as-non-object' => true ), 'do_cond' );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( ! \$do_cond ) break;" );
        $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode();
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "} // do..while" );
        $newNodes[] = eZTemplateNodeTool::createVariableUnsetNode( 'do_cond' );

        $loop->cleanup();

        return $newNodes;
    }

    /*!
     * Actually executes the function and its children (in processed mode).
     */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $loop = new eZTemplateLoop( eZTemplateDoFunction::FUNCTION_NAME,
                                    $functionParameters, $functionChildren, $functionPlacement,
                                    $tpl, $textElements, $rootNamespace, $currentNamespace );

        if ( !$loop->initialized() )
            return;

        if ( !isset( $functionParameters['condition'] ) )
        {
            eZDebug::writeError( "Not enough arguments passed to 'do' function"  );
            return;
        }

        do
        {
            $loop->setSequenceVar(); // set sequence variable (if specified)
            $loop->processDelimiter();
            $loop->resetIteration();

            if ( $loop->processChildren() )
                break;

            $loop->incrementSequence();
        } while ( $tpl->elementValue( $functionParameters['condition'], $rootNamespace, $currentNamespace, $functionPlacement ) );

        $loop->cleanup();
    }

    /*!
     * Returns true, telling the template parser that the function can have children.
     */
    function hasChildren()
    {
        return true;
    }
}

?>
