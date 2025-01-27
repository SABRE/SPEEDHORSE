<?php
//
// Definition of eZNetStorage class
//
// Created on: <29-Sep-2005 10:01:32 hovik>
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

/*! \file eznetstorage.php
*/

/*!
  \class eZNetStorage eznetstorage.php
  \brief The class eZNetStorage does

*/


class eZNetStorage extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZNetStorage($row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => true ),
                                         "id2" => array( 'name' => 'ID2',
                                                         'datatype' => 'string',
                                                         'default' => '',
                                                         'required' => true ),
                                         "value" => array( 'name' => 'Value',
                                                           'datatype' => 'longtext',
                                                           'default' => '',
                                                           'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array(),
//                       "increment_key" => "id",
                      "class_name" => "eZNetStorage",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezx_ezpnet_storage" );
    }

    /*!
     \static
     Get value based on key

     \param Key
     \param Additional Key.
     \return value
    */
    static function get( $key, $key2 = false )
    {
        if ( is_array( $key ) )
        {
            $key = serialize( $key );
        }
        $key = md5( $key );

        $matchArray = array( 'id' => $key );

        if ( $key2 === false )
        {
            if ( is_array( $key2 ) )
            {
                $key2 = serialize( $key2 );
            }
            $key2 = md5( $key2 );
            $matchArray['id2'] = $key2;
        }

        $result = eZPersistentObject::fetchObject( eZNetStorage::definition(),
                                                   array( 'value' ),
                                                   $matchArray,
                                                   false );
        if ( !$result )
        {
            return false;
        }

        return unserialize( eZNetCrypt::decrypt( $result['value'] ) );
    }

    /*!
     \static
     Get value array based on key. Will return all instances stored by key, but with different key2.

     \param Key.
     \return Array of values
    */
    static function getArray( $key  )
    {
        if ( is_array( $key ) )
        {
            $key = serialize( $key );
        }
        $key = md5( $key );
        $matchArray = array( 'id' => $key );

        $resultArray = eZPersistentObject::fetchObjectList( eZNetStorage::definition(),
                                                            array( 'value' ),
                                                            $matchArray );
        if ( !$resultArray ||
             !count( $resultArray ) )
        {
            return false;
        }

        $returnArray = array();

        foreach( $resultArray as $result )
        {
            $returnArray[] = unserialize( eZNetCrypt::decrypt( $result['value'] ) );
        }

        return $returnArray;
    }

    /*!
     \static
     Set a value to the DB storage.

     \param key
     \param value
     \param key2 ( additional key )
    */
    static function set( $key, $value, $key2 = '' )
    {
        if ( is_array( $key ) )
        {
            $key = serialize( $key );
        }
        if ( is_array( $key2 ) )
        {
            $key2 = serialize( $key2 );
        }

        $key = md5( $key );
        $key2 = md5( $key2 );

        $db = eZDB::instance();

        // Need to check for type of db instance.
        // If it's oracle we should use special SQL to store a big text to clob field
        // due to via simple sql insert it's not allowed to pass strings more than 4000 characters.
        // Simple solution: need to use binded variables.
        if ( $db->databaseName() == 'oracle' )
        {
            $content = eZNetCrypt::encrypt( serialize( $value ) );

            $def = eZNetStorage::definition();
            $filedList = implode( ", ", array_keys( $def['fields'] ) );
            $values = "'$key', '$key2', :value";
            $clobData = array( 'value' => $content );

            eZNetUtils::insertOracleCLOBData( $db->DBConnection, $def['name'], $filedList, $values, $clobData );
        }
        else
        {
            $storage = eZPersistentObject::fetchObject( eZNetStorage::definition(),
                                                        null,
                                                        array( 'id' => $key,
                                                               'id2' => $key2 ) );
            if ( !$storage )
            {
                $storage = new eZNetStorage( array( 'id' => $key,
                                                    'id2' => $key2 ) );
            }

            $storage->setAttribute( 'value', eZNetCrypt::encrypt( serialize( self::utf8encode( $value ) ) ) );
            $storage->store();
        }
    }

    /**
     * Make sure data is utf8 encoded so it does not crate issues when mysql is in strict mode.
     *
     * @param string $data
     * @return string
     */
    protected static function utf8encode( $data )
    {
        if ( is_array( $data ) )
        {
            foreach( $data as $key => $value )
            {
                $data[$key] = self::utf8encode( $value );
            }
            return $data;
        }
        return utf8_encode( $data );
    }
}

?>
