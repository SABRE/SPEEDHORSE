<?php
//
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Network
// SOFTWARE RELEASE: 4.5.0
// COPYRIGHT NOTICE: Copyright (C) 2007 eZ Systems AS
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

/*! \file eznetlargeobjectstorage.php
*/

/*!
  \class eZNetLargeObjectStorage eznetlargeobjectstorage.php
  \brief The class eZNetLargeObjectStorage does

*/

class eZNetLargeObjectStorage extends eZPersistentObject
{
        /*!
     Constructor
    */
    function eZNetLargeObjectStorage($row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "class_name" => array( 'name' => 'Table',
                                                           'datatype' => 'string',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'key_hash' => array( 'name' => 'KeyHash',
                                                              'datatype' => 'string',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'field' => array( 'name' => 'Field',
                                                           'datatype' => 'string',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'offset' => array( 'name' => 'Offset',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         'data' => array( 'name' => 'Data',
                                                          'datatype' => 'longtext',
                                                          'default' => '',
                                                          'required' => true ) ),
                      "keys" => array( 'class_name', 'key_hash', 'offset', 'field' ),
                      "increment_key" => "offset",
                      "class_name" => "eZNetLargeObjectStorage",
                      "sort" => array( "offset" => "asc" ),
                      "name" => "ezx_ezpnet_large_store" );
    }

    /*!
     \static
     Insert, and split value into tables.

     \param eZPersistenObject
     \param Field name
    */
    static function storeData( $object, $field, $sql )
    {
        // Remove old data
        eZNetLargeObjectStorage::removeData( $object, $field, $sql );

        // Get key hash
        $keyHash = eZNetLargeObjectStorage::keyHash( $object, $sql );

        // Get class name
        $className = eZNetLargeObjectStorage::className( $object );

        // Get data to store.
        $data = $object->attribute( $field );

        // Split the data into smaller parts, and store them.
        $offset = 0;
        $length = eZNetLargeObject::MaxPacketSize;
        while( $subString = substr( $data, $offset, $length ) )
        {
            $objectStorage = new eZNetLargeObjectStorage( array( 'class_name' => $className,
                                                                 'key_hash' => $keyHash,
                                                                 'field' => $field,
                                                                 'offset' => $offset,
                                                                 'data' => $subString ) );
            $objectStorage->store();
            $offset += $length;
        }
    }

    /*!
     \static
     Fetch data for specified classname and key hash

     \param class definition
     \param value array
     \param field

     \return field data
    */
    static function dataByRows( $definition, $rows, $field )
    {
        $resultSet = eZNetLargeObjectStorage::fetchObjectList( eZNetLargeObjectStorage::definition(),
                                                               array( 'data' ),
                                                               array( 'class_name' => $definition['class_name'],
                                                                      'key_hash' => eZNetLargeObjectStorage::keyHashByRows( $definition, $rows ),
                                                                      'field' => $field ),
                                                               array( 'offset' => 'asc' ),
                                                               null,
                                                               false );
        if ( !$resultSet )
        {
            $data = eZNetLargeObject::LargeObjectStore;
        }
        else
        {
            $data = '';
            foreach( $resultSet as $result )
            {
                $data .= $result['data'];
            }
        }

        return $data;
    }

    /*!
     \static
     Fetch data for specified classname and key array.

    \param eZPersistenObject
    \param field
    */
    static function data( $object, $field )
    {
        $resultSet = eZNetLargeObjectStorage::fetchObjectList( eZNetLargeObjectStorage::definition(),
                                                               array( 'data' ),
                                                               array( 'class_name' => eZNetLargeObjectStorage::className( $object ),
                                                                      'key_hash' => eZNetLargeObjectStorage::keyHash( $object ),
                                                                      'field' => $field ),
                                                               array( 'offset' => 'asc' ),
                                                               null,
                                                               false );

        if ( !$resultSet )
        {
            $data = eZNetLargeObject::LargeObjectStore;
        }
        else
        {
            $data = '';
            foreach( $resultSet as $result )
            {
                $data .= $result['data'];
            }
        }

        return $data;
    }

    /*!
     \static
     Removes data.
     If field is an array, it'll remove rows matching a field entry.
     */
    static function removeData( $object, $field, $sql = false )
    {
        if ( is_array( $field ) )
        {
            $field = array( $field );
        }
        eZNetLargeObjectStorage::removeObject( eZNetLargeObjectStorage::definition(),
                                               array( 'class_name' => eZNetLargeObjectStorage::className( $object ),
                                                      'key_hash' => eZNetLargeObjectStorage::keyHash( $object, $sql ),
                                                      'field' => $field ) );
    }

    /*!
     \static
     Key hash by rows

     \param Class definition
     \param rows

     \return keyHash
    */
    static function keyHashByRows( $definition, $rows )
    {
        $keys = $definition['keys'];

        // Create key md5 hash
        $keyArray = array();
        foreach ( $keys as $key )
        {
            $keyArray[$key] = $rows[$key];
        }

        return md5( serialize( $keyArray ) );
    }

    /*!
     \static
     Create key hash

     \param eZPersistenObject

     \return key hash
    */
    static function keyHash( $object, $sql = false )
    {
        // Get object definition
        $definition = $object->definition();
        $keys = $definition['keys'];

        // Create key md5 hash
        $keyArray = array();
        foreach ( $keys as $key )
        {
            $keyArray[$key] = (string)$object->attribute( $key );
        }

        return md5( serialize( $keyArray ) );
    }

    /*!
     \static
     Get class name from eZPersistenObject

     \param object

     \return class name
    */
    static function className( $object )
    {
        $definition = $object->definition();
        return $definition['class_name'];
    }
}


?>
