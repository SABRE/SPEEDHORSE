<?php
//
// Definition of eZNetSOAPObject class
//
// Created on: <15-Jul-2005 00:38:44 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Network
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
//     Att: eZ Systems AS Licensing Dept., Klostergata 30, N-3732 Skien, Norway
//
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file eznetsoapobject.php
*/

/*!
  \class eZNetSOAPObject eznetsoapobject.php
  \brief The class eZNetSOAPObject does

*/

class eZNetSOAPObject extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZNetSOAPObject( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     Fetch missing object list specified by eZNetSOAP object ( using the eZNetSOAPLog )
    */
    static function fetchMissingObjectList( $eZNetSOAP )
    {
        $latestLogEntry = $eZNetSOAP->latestLogEntry();
        $lastEventTS = 0;

        if ( $latestLogEntry )
        {
            $lastEventTS = $latestLogEntry->attribute( 'timestamp' );
        }

        $className = $eZNetSOAP->attribute( 'local_class' );
        $classDefinition = call_user_func( array( $className, 'definition' ) );

        return eZPersistentObject::fetchObjectList( $classDefinition,
                                                    null,
                                                    array( 'timestamp' => array( '>', $lastEventTS ) ) );
    }

    /*!
     Create string representation of current object
    */
    function soapString()
    {
        $definition = $this->definition();

        $objectArray = array();
        foreach( array_keys( $definition['fields'] ) as $attributeName )
        {
            $objectArray[$attributeName] = $this->attribute( $attributeName );
        }

        return serialize( $objectArray );
    }

    /*!
      Get local ID
    */
    function soapLocalID()
    {
        return $this->attribute( 'id' );
    }
}

?>
