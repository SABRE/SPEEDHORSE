<?php
//
// Definition of eZTemplateDelimitFunction class
//
// Created on: <01-Mar-2002 13:49:07 amos>
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
  \class eZTemplateDelimitFunction eztemplatedelimitfunction.php
  \ingroup eZTemplateFunctions
  \brief Displays left and right delimiter in templates

  This class iss a template function for outputting the left and right delimiters.
  Since the left and right delimiters are always parsed by the template engine
  it's not possible to output these characters. By registering an instance of this
  class as template functions you can get these characters with {ldelim} and {rdelim}.

  The name of these functions can also be controlled by passing the names to the
  constructor.

  Example:
\code
$tpl->registerFunctions( new eZTemplateDelimitFunction() );
// or custom names
$tpl->registerFunctions( new eZTemplateDelimitFunction( "l", "r" ) );
// alternatively
$obj = new eZTemplateDelimitFunction();
$tpl->registerFunction( "ldelim", $obj );
$tpl->registerFunction( "rdelim", $obj );
\endcode
*/

class eZTemplateDelimitFunction
{
    /*!
     Initializes the object with a name for the left and right delimiter.
     Default is ldelim for left and rdelim for right.
    */
    function eZTemplateDelimitFunction()
    {
        $this->LName = 'ldelim';
        $this->RName = 'rdelim';
    }

    /*!
     Returns an array of the function names, required for eZTemplate::registerFunctions.
    */
    function functionList()
    {
        return array( $this->LName, $this->RName );
    }

    /*!
     Returns an array with hints for the template compiler.
    */
    function functionTemplateHints()
    {
        return array(
            $this->LName => array( 'parameters' => false, 'static' => false, 'tree-transformation' => true ),
            $this->RName => array( 'parameters' => false, 'static' => false, 'tree-transformation' => true )
        );
    }

    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, $parameters, $privateData )
    {
        $newNodes = array();

        if ( $functionName == $this->LName )
        {
            $newNodes = array ( eZTemplateNodeTool::createTextNode( $tpl->leftDelimiter() ) );
        }
        else
        {
            $newNodes = array ( eZTemplateNodeTool::createTextNode( $tpl->rightDelimiter() ) );
        }
        return $newNodes;
    }

    /*!
     Outputs the left or right delimiter if the function names match.
    */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $nspace, $current_nspace )
    {
        switch ( $functionName )
        {
            case $this->LName:
            {
                $textElements[] = $tpl->leftDelimiter();
            } break;
            case $this->RName:
            {
                $textElements[] = $tpl->rightDelimiter();
            } break;
        }
    }

    /*!
     Returns false, telling the template parser that this is a single tag.
    */
    function hasChildren()
    {
        return false;
    }

    /// The name of the left delimiter tag
    public $LName;
    /// The name of the right delimiter tag
    public $RName;
}

?>
