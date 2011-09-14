<?php
//
// Definition of eZClusterFileFailure class
//
// Created on: <16-May-2007 09:04:53 amos>
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
 \class eZClusterFileHandler ezclusterfilefailure.php
 Special failure object which can be used by some of the cluster functions
 to inform about failures or special exceptions.

 Currently used by the *processCache* function to report that the retrieve callback
 failed to retrieve data because of expiration.
 */
class eZClusterFileFailure
{
    // Error codes:
    // 1 - file expired
    // 2 - file contents must be manually generated
    function eZClusterFileFailure( $errno, $message = false )
    {
        $this->Errno = $errno;
        $this->Message = $message;
    }

    /*!
     Returns the error number.
     */
    function errno()
    {
        return $this->Errno;
    }

    /*!
     Returns the error message if there is one.
     */
    function message()
    {
        return $this->Message;
    }
}
?>
