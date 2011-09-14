<?php
//
// Created on: <01-Oct-2002 13:23:07 amos>
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
  Contains all the basic kernel and kernel related error codes.
*/

class eZError
{

/*!
 Access denied to object or module.
*/
const KERNEL_ACCESS_DENIED = 1;
/*!
 The object could not be found.
*/
const KERNEL_NOT_FOUND = 2;
/*!
 The object is not available.
*/
const KERNEL_NOT_AVAILABLE = 3;
/*!
 The object is moved.
*/
const KERNEL_MOVED = 4;
/*!
 The language is not found.
*/
const KERNEL_LANGUAGE_NOT_FOUND = 5;

/*!
 The module could not be found.
*/
const KERNEL_MODULE_NOT_FOUND = 20;
/*!
 The module view could not be found.
*/
const KERNEL_MODULE_VIEW_NOT_FOUND = 21;
/*!
 The module or view is not enabled.
*/
const KERNEL_MODULE_DISABLED = 22;


/*!
 No database connection
*/
const KERNEL_NO_DB_CONNECTION = 50;

//Shop system error codes
const SHOP_OK = 0;
const SHOP_NOT_A_PRODUCT = 1;
const SHOP_BASKET_INCOMPATIBLE_PRODUCT_TYPE = 2;
const SHOP_PREFERRED_CURRENCY_DOESNOT_EXIST = 3;
const SHOP_PREFERRED_CURRENCY_INACTIVE = 4;


}

?>
