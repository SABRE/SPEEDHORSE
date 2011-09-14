<?php
//
// $Id$
//
// Definition of eZNullDB class
//
// Created on: <12-Feb-2002 15:54:17 bf>
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
  \class eZNullDB eznulldb.php
  \ingroup eZDB
  \brief The eZNullDB class provides a interface which does nothing

  This class is returned when a proper implementation could not be found.
*/

class eZNullDB extends eZDBInterface
{
    /*!
      Does nothing.
    */
    function eZNullDB( $parameters )
    {
        $this->eZDBInterface( $parameters );
    }

    /*!
      Does nothing.
    */
    function databaseName()
    {
        return 'null';
    }

    /*!
      Returns false.
    */
    function query( $sql, $server = false )
    {
        return false;
    }

    /*!
      Returns false.
    */
    function arrayQuery( $sql, $params = array(), $server = false )
    {
        return false;
    }

    /*!
      Does nothing.
    */
    function lock( $table )
    {
    }

    /*!
      Does nothing.
    */
    function unlock()
    {
    }

    /*!
      Does nothing.
    */
    function begin()
    {
    }

    /*!
      Does nothing.
    */
    function commit()
    {
    }

    /*!
      Does nothing.
    */
    function rollback()
    {
    }

    /*!
      Returns false.
    */
    function lastSerialID( $table = false, $column = false )
    {
        return false;
    }

    /*!
      Returns $str.
    */
    function escapeString( $str )
    {
        return $str;
    }

    /*!
      Does nothing.
    */
    function close()
    {
    }
}

?>
