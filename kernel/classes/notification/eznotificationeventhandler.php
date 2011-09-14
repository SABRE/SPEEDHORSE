<?php
//
// Definition of eZNotificationEventHandler class
//
// Created on: <09-May-2003 16:06:26 sp>
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
  \class eZNotificationEventHandler eznotificationeventhandler.php
  \brief The class eZNotificationEventHandler does

*/

class eZNotificationEventHandler
{
    const EVENT_HANDLED = 0;
    const EVENT_SKIPPED = 1;
    const EVENT_UNKNOWN = 2;
    const EVENT_ERROR = 3;

    /*!
     Constructor
    */
    function eZNotificationEventHandler( $idString, $name )
    {
        $this->IDString = $idString;
        $this->Name = $name;
    }

    function attributes()
    {
        return array( 'id_string',
                      'name' );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        if ( $attr == 'id_string' )
        {
            return $this->IDString;
        }
        else if ( $attr == 'name' )
        {
            return $this->Name;
        }

        eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
        return null;
    }

    function handle( $event )
    {
        return true;
    }

    /*!
     Cleanup any specific tables or other resources.
    */
    function cleanup()
    {
    }

    function fetchHttpInput( $http, $module )
    {
        return true;
    }

    function storeSettings( $http, $module )
    {
        return true;
    }

    public $IDString = false;
    public $Name = false;
}

?>
