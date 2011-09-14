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
  /deprecated Use eZError class constants instead
*/

eZDebug::writeWarning( "All the constants in " . __FILE__ . " are deprecated, use eZError class constants instead" );

/*!
 Access denied to object or module.
*/
define( 'EZ_ERROR_KERNEL_ACCESS_DENIED', 1 );
/*!
 The object could not be found.
*/
define( 'EZ_ERROR_KERNEL_NOT_FOUND', 2 );
/*!
 The object is not available.
*/
define( 'EZ_ERROR_KERNEL_NOT_AVAILABLE', 3 );
/*!
 The object is moved.
*/
define( 'EZ_ERROR_KERNEL_MOVED', 4 );
/*!
 The language is not found.
*/
define( 'EZ_ERROR_KERNEL_LANGUAGE_NOT_FOUND', 5 );

/*!
 The module could not be found.
*/
define( 'EZ_ERROR_KERNEL_MODULE_NOT_FOUND', 20 );
/*!
 The module view could not be found.
*/
define( 'EZ_ERROR_KERNEL_MODULE_VIEW_NOT_FOUND', 21 );
/*!
 The module or view is not enabled.
*/
define( 'EZ_ERROR_KERNEL_MODULE_DISABLED', 22 );


/*!
 No database connection
*/
define( 'EZ_ERROR_KERNEL_NO_DB_CONNECTION', 50 );

?>
