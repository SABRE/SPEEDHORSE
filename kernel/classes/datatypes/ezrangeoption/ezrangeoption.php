<?php
//
// Definition of eZRangeOption class
//
// Created on: <17-���-2003 16:17:18 sp>
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
  \class eZRangeOption ezrangeoption.php
  \ingroup eZDatatype
  \brief The class eZRangeOption does

*/

class eZRangeOption
{
    /*!
     Constructor
    */
    function eZRangeOption( $name )
    {
        $this->Name = $name;
        $this->Options = array();
        $this->OptionCount = 0;
    }

    /*!
     Sets the name of the option
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
     Returns the name of the option set.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
     \return list of supported attributes
    */
    function attributes()
    {
        return array( 'name',
                      'start_value',
                      'stop_value',
                      'step_value',
                      'option_list' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            }break;
            case "start_value" :
            {
                return $this->StartValue;
            }break;
            case "stop_value" :
            {
                return $this->StopValue;
            }break;
            case "step_value" :
            {
                return $this->StepValue;
            }break;
            case "option_list" :
            {
                return $this->Options;
            }break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", __METHOD__ );
                return null;
            }break;
        }
    }

    function addOption( $valueArray )
    {
        $this->Options[] = array( "id" => $this->OptionCount,
                                  "value" => $valueArray['value'],
                                  'additional_price' => 0,
                                  "is_default" => false );

        $this->OptionCount += 1;
    }

    function decodeXML( $xmlString )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom->loadXML( $xmlString );

        if ( $xmlString != "" )
        {
            // set the name of the node
            $rangeOptionElement = $dom->documentElement;
            $startValue = $rangeOptionElement->getAttribute( 'start_value' );
            $stopValue = $rangeOptionElement->getAttribute( 'stop_value' );
            $stepValue = $rangeOptionElement->getAttribute( 'step_value' );
            if ( $stepValue == 0 )
                $stepValue = 1;
            $this->StartValue = $startValue;
            $this->StopValue = $stopValue;
            $this->StepValue = $stepValue;


            $nameNode = $dom->getElementsByTagName( "name" )->item( 0 );
            $this->setName( $nameNode->textContent );

            for ( $i = $startValue; $i <= $stopValue; $i += $stepValue )
            {
                $this->addOption( array( 'value' => $i,
                                         'additional_price' => 0 ) );
            }
        }
        else
        {
            $this->StartValue = 0;
            $this->StopValue = 0;
            $this->StepValue = 0;
        }
    }

    /*!
     Will return the XML string for this option set.
    */
    function xmlString( )
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );

        $root = $doc->createElement( "ezrangeoption" );
        $root->setAttribute( "start_value", $this->StartValue );
        $root->setAttribute( "stop_value", $this->StopValue );
        $root->setAttribute( "step_value", $this->StepValue );
        $doc->appendChild( $root );

        $name = $doc->createElement( "name", $this->Name );
        $root->appendChild( $name );

        $xml = $doc->saveXML();

        return $xml;
    }

    function setStartValue( $value )
    {
        $this->StartValue = $value;
    }

    function setStopValue( $value )
    {
        $this->StopValue = $value;
    }

    function setStepValue( $value )
    {
        $this->StepValue = $value;
    }


        /// Contains the Option name
    public $Name;

    /// Contains the Options
    public $Options;

    /// Contains the option counter value
    public $OptionCount;
    public $StartValue;
    public $StopValue;
    public $StepValue;
}

?>
