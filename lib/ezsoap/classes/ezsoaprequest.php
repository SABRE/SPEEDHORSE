<?php
//
// Definition of eZSOAPRequest class
//
// Created on: <19-Feb-2002 15:42:03 bf>
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
  \class eZSOAPRequest ezsoaprequest.php
  \ingroup eZSOAP
  \brief eZSOAPRequest handles SOAP request messages

*/

class eZSOAPRequest extends eZSOAPEnvelope
{
    /*!
     Constructs a new eZSOAPRequest object. You have to provide the request name
     and the target namespace for the request.

     \param name
     \param namespace
     \param parameters, assosiative array, example: array( 'param1' => 'value1, 'param2' => 'value2' )
    */
    function eZSOAPRequest( $name="", $namespace="", $parameters = array() )
    {
        $this->Name = $name;
        $this->Namespace = $namespace;

        // call the parents constructor
        $this->eZSOAPEnvelope();

        foreach( $parameters as $name => $value )
        {
            $this->addParameter( $name, $value );
        }
    }

    /*!
      Returns the request name.
    */
    function name()
    {
        return $this->Name;
    }

   /** Returns the request target namespace.
     *
     * @since 4.1.4 (renamed from namespace() for php 5.3 compatibility)
     * @return string
    */
    function ns()
    {
        return $this->Namespace;
    }

    /*!
     Adds a new attribute to the body element.

     \param attribute name
     \param attribute value
     \param prefix
    */
    function addBodyAttribute( $name, $value, $prefix = false )
    {
        $this->BodyAttributes[( $prefix ? $prefix . ':' : '' ) . $name] = $value;
    }

    /*!
      Adds a new parameter to the request. You have to provide a prameter name
      and value.
    */
    function addParameter( $name, $value )
    {
        $this->Parameters[] = new eZSOAPParameter( $name, $value );
    }

    /*!
      Returns the request payload
    */
    function payload()
    {
        $doc = new DOMDocument( "1.0" );
        $doc->name = 'eZSOAP message';

        $root = $doc->createElementNS( eZSOAPEnvelope::ENV, eZSOAPEnvelope::ENV_PREFIX . ':Envelope' );

        $root->setAttribute( 'xmlns:' . eZSOAPEnvelope::XSI_PREFIX, eZSOAPEnvelope::SCHEMA_INSTANCE );
        $root->setAttribute( 'xmlns:' . eZSOAPEnvelope::XSD_PREFIX, eZSOAPEnvelope::SCHEMA_DATA );
        $root->setAttribute( 'xmlns:' . eZSOAPEnvelope::ENC_PREFIX, eZSOAPEnvelope::ENC );

        // add the body
        $body = $doc->createElement( eZSOAPEnvelope::ENV_PREFIX . ':Body' );
        $body->setAttribute( 'xmlns:req', $this->Namespace );

        foreach( $this->BodyAttributes as $name => $value )
        {
            $body->setAttribute( $name, $value );
        }

        $root->appendChild( $body );

        // add the request
        $request = $doc->createElement( 'req:' . $this->Name );

        // add the request parameters
        foreach ( $this->Parameters as $parameter )
        {
            $param = eZSOAPCodec::encodeValue( $doc, $parameter->name(), $parameter->value() );

            if ( $param == false )
            {
                eZDebug::writeError( "Error encoding data for payload: " . $parameter->name(), __METHOD__ );
                continue;
            }
            $request->appendChild( $param );
        }

        $body->appendChild( $request );

        $doc->appendChild( $root );
        return $doc->saveXML();
    }

    /// The request name
    public $Name;

    /// The request target namespace
    public $Namespace;

    /// Additional body element attributes.
    public $BodyAttributes = array();

    /// Contains the request parameters
    public $Parameters = array();
}

?>
