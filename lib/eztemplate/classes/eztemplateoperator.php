<?php
//
// Definition of eZTemplateOperator class
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
  \class eZTemplateOperator eztemplateoperator.php
  \ingroup eZTemplateOperators
  \brief Base documentation class for operators

  \note This class is never used but only exists for documentation purposes.
*/

class eZTemplateOperator
{
    /*!
     Returns the template operators which are registered when using eZTemplate::registerOperators()
    */
    function operatorList()
    {
        $operationList = array();
        return $operationList;
    }

    /*!
     Returns an array of named parameters, this allows for easier retrieval
     of operator parameters. This also requires the function modify() has an extra
     parameter called $named_params.

     The position of each element (starts at 0) represents the position of the original
     sequenced parameters. The key of the element is used as parameter name, while the
     contents define the type and requirements.
     The keys of each element content is:
     * type - defines the type of parameter allowed
     * required - boolean which says if the parameter is required or not, if missing
                  and required an error is displayed
     * default - the default value if the parameter is missing
    */
    function namedParameterList()
    {
        return array();
    }

    /*!
     Modifies the input variable $value and sets the output result in the same variable.

     \note Remember to use references on the function arguments.
    */
    function modify( /*! The operator element, can be used for doing advanced querying but should be avoided. */
                     $element,
                     /*! The template object which called this class */
                     $tpl,
                     /*! The name of this operator */
                     &$op_name,
                     /*! The parameters for this operator */
                     &$op_params,
                     /*! The namespace which this operator works in */
                     &$namespace,
                     /*! The current namespace for functions, this is usually used in functions
                         for setting new variables. */
                     /*! The input/output value */
                     &$value,
                     /*! The parameters as named lookups, only required if namedParameterList() is defined.
                         The values of each parameter is also fetched for you. */
                     &$named_params )
    {
    }

}

?>
