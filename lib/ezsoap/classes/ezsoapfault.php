<?php
//
// Definition of eZSOAPFault class
//
// Created on: <30-May-2002 15:51:41 bf>
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
  \class eZSOAPFault ezsoapfault.php
  \ingroup eZSOAP
  \brief eZSOAPFault handles SOAP fault messages

*/

class eZSOAPFault
{
    /*!
     Constructs a new eZSOAPFault object
    */
    function eZSOAPFault( $faultCode = "", $faultString = "" )
    {
        $this->FaultCode = $faultCode;
        $this->FaultString = $faultString;
    }

    /*!
     Returns the fauls code.
    */
    function faultCode()
    {
        return $this->FaultCode;
    }

    /*!
     Returns the fauls string.
    */
    function faultString()
    {
        return $this->FaultString;
    }

    /// Contains the fault code
    public $FaultCode;

    /// Contains the fault string
    public $FaultString;
}

?>
