<?php
//
// Definition of eZPrice class
//
// Created on: <26-Nov-2002 12:26:52 wy>
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
  \class eZPrice ezprice.php
  \ingroup eZDatatype
*/



class eZPrice extends eZSimplePrice
{
    /*!
     Constructor
    */
    function eZPrice( $classAttribute, $contentObjectAttribute, $storedPrice = null )
    {
        eZSimplePrice::eZSimplePrice( $classAttribute, $contentObjectAttribute, $storedPrice );

        $isVatIncluded = ( $classAttribute->attribute( eZPriceType::INCLUDE_VAT_FIELD ) == 1 );
        $VATID = $classAttribute->attribute( eZPriceType::VAT_ID_FIELD );
        $this->setVatIncluded( $isVatIncluded );
        $this->setVatType( $VATID );
    }

    /// \privatesection
}

?>
