<?php

include_once('extension/all2eqrcode/classes/all2eQRCodeClass.php');
class all2eQRCodeOperators
{
    /*!
     Constructor
    */
    function all2eQRCodeOperators()
    {
        $this->Operators = array('qrcode');
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
       \return true to tell the template engine that the parameter list
      exists per operator type, this is needed for operator classes
      that have multiple operators.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
       The operator has two parameters.
       See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'qrcode' => array('text' => array('type' => 'string',
                                                        'required' => true,
                                                        'default' => 'qr-code' ),
                                        'type' => array('type' => 'string',
                                                        'required' => true,
                                                        'default' => 'text' ),
                                        'width' => array('type' => 'string',
                                                        'required' => false,
                                                        'default' => '250' ),
                                        'height' => array('type' => 'string',
                                                        'required' => false,
                                                        'default' => '250' )
                                      )
                  );
    }

    /*!
       Executes the needed operator(s).
       Checks operator names, and calls the appropriate functions.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace,
                     &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'qrcode':
            {
                $operatorValue = all2eQRCodeClass::encode( $namedParameters['type'], $namedParameters['text'], $namedParameters['width'], $namedParameters['height'] );
            } break;
        }
    }
    
    /// \privatesection
    var $Operators;
}

?>
