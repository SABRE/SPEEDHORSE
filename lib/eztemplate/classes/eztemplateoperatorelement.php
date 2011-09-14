<?php
//
// Definition of eZTemplateOperatorElement class
//
// Created on: <01-Mar-2002 13:49:50 amos>
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

/*! \defgroup eZTemplateOperators Template operators
    \ingroup eZTemplate
*/

/*!
  \class eZTemplateOperatorElement eztemplateoperatorelement.php
  \ingroup eZTemplateElements
  \brief Represents an operator element in the template tree.

  This class represents an operator with it's parameters.
*/

class eZTemplateOperatorElement
{
    /*!
     Initializes the operator with a name and parameters.
    */
    function eZTemplateOperatorElement( $name, $params, $resource = null, $templateName = null )
    {
        $this->Name = $name;
        $this->Params = $params;
        $this->Resource = $resource;
        $this->TemplateName = $templateName;
    }

    function setResourceRelation( $resource )
    {
        $this->Resource = $resource;
    }

    function setTemplateNameRelation( $templateName )
    {
        $this->TemplateName = $templateName;
    }

    function resourceRelation()
    {
        return $this->Resource;
    }

    function templateNameRelation()
    {
        return $this->TemplateName;
    }

    /*!
     Returns a reference to the name.
    */
    function &name()
    {
        return $this->Name;
    }

    function serializeData()
    {
        return array( 'class_name' => 'eZTemplateOperatorElement',
                      'parameters' => array( 'name', 'parameters', 'resource', 'template_name' ),
                      'variables' => array( 'name' => 'Name',
                                            'parameters' => 'Params',
                                            'resource' => 'Resource',
                                            'template_name' => 'TemplateName' ) );
    }

    /*!
     Process the operator and sets $value.

    */
    function process( $tpl, &$value, $nspace, $current_nspace )
    {
        $named_params = array();
        $param_list = $tpl->operatorParameterList( $this->Name );
        $i = 0;
        foreach ( $param_list as $param_name => $param_type )
        {
            if ( !isset( $this->Params[$i] ) or
                 $this->Params[$i]["type"] == "null" )
            {
                if ( $param_type["required"] )
                {
                    $tpl->warning( "eZTemplateOperatorElement", "Parameter '$param_name' ($i) missing" );
                    $named_params[$param_name] = $param_type["default"];
                }
                else
                {
                    $named_params[$param_name] = $param_type["default"];
                }
            }
            else
            {
                $param_data = $this->Params[$i];
                $named_params[$param_name] = $tpl->elementValue( $param_data, $nspace );
            }
            ++$i;
        }
        if ( $param_list !== null )
            $tpl->doOperator( $this, $nspace, $current_nspace, $value, $this->Name, $this->Params, $named_params );
        else
            $tpl->doOperator( $this, $nspace, $current_nspace, $value, $this->Name, $this->Params );
    }

    /*!
     Returns a reference to the parameter array.
    */
    function &parameters()
    {
        return $this->Params;
    }

    /// The operator name
    public $Name;
    /// The paramer array
    public $Params;
    public $Resource;
    public $TemplateName;
}

?>
