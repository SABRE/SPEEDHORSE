<?php
//
// Definition of eZSOAPEnvelope class
//
// Created on: <28-Feb-2002 15:54:36 bf>
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

/*! \defgroup eZSOAP SOAP communication library */

/*!
  \class eZSOAPEnvelope ezsoapenvelope.php
  \ingroup eZSOAP
  \brief SOAP envelope handling and definition

*/

class eZSOAPEnvelope
{
    const ENV = "http://schemas.xmlsoap.org/soap/envelope/";
    const ENC = "http://schemas.xmlsoap.org/soap/encoding/";
    const SCHEMA_INSTANCE = "http://www.w3.org/2001/XMLSchema-instance";
    const SCHEMA_DATA = "http://www.w3.org/2001/XMLSchema";

    const ENV_PREFIX = "SOAP-ENV";
    const ENC_PREFIX = "SOAP-ENC";
    const XSI_PREFIX = "xsi";
    const XSD_PREFIX = "xsd";

    const INT = 1;
    const STRING = 2;

    /*!
      Constructs a new SOAP envelope object.
    */
    function eZSOAPEnvelope( )
    {
        $this->Header = new eZSOAPHeader();
        $this->Body = new eZSOAPBody();
    }

    /// Contains the header object
    public $Header;

    /// Contains the body object
    public $Body;
}

?>
