<?php
//
// Created on: <07-Apr-2005 13:17:57 amos>
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
  Contains all the error codes for the shop module.
  /deprecated Use eZError class constants instead
*/

eZDebug::writeWarning( "All the constants in " . __FILE__ . " are deprecated, use eZError class constants instead" );

/*!
 The object is not a product.
*/
define( 'EZ_ERROR_SHOP_OK', 0 );
define( 'EZ_ERROR_SHOP_NOT_A_PRODUCT', 1 );
define( 'EZ_ERROR_SHOP_BASKET_INCOMPATIBLE_PRODUCT_TYPE', 2 );
define( 'EZ_ERROR_SHOP_PREFERRED_CURRENCY_DOESNOT_EXIST', 3 );
define( 'EZ_ERROR_SHOP_PREFERRED_CURRENCY_INACTIVE', 4 );
?>
