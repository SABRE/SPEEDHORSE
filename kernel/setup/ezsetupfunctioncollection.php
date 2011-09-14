<?php
//
// Definition of eZSetupFunctionCollection class
//
// Created on: <02-Nov-2004 13:23:10 dl>
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
  \class eZSetupFunctionCollection ezsetupfunctioncollection.php
  \brief The class eZSetupFunctionCollection does

*/

class eZSetupFunctionCollection
{
    /*!
     Constructor
    */
    function eZSetupFunctionCollection()
    {
    }


    function fetchFullVersionString()
    {
        return array( 'result' => eZPublishSDK::version() );
    }

    function fetchMajorVersion()
    {
        return array( 'result' => eZPublishSDK::majorVersion() );
    }

    function fetchMinorVersion()
    {
        return array( 'result' => eZPublishSDK::minorVersion() );
    }

    function fetchRelease()
    {
        return array( 'result' => eZPublishSDK::release() );

    }

    function fetchState()
    {
        return array( 'result' => eZPublishSDK::state() );
    }

    function fetchIsDevelopment()
    {
        return array( 'result' => eZPublishSDK::developmentVersion() ? true : false );
    }

    function fetchDatabaseVersion( $withRelease = true )
    {
        return array( 'result' => eZPublishSDK::databaseVersion( $withRelease ) );
    }

    function fetchDatabaseRelease()
    {
        return array( 'result' => eZPublishSDK::databaseRelease() );
    }
}

?>
