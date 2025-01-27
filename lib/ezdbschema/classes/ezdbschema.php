<?php
//
// Created on: <28-Jan-2004 15:46:30 dr>
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
  \class eZDbSchema ezdbschema.php
  \ingroup eZDbSchema
  \brief A factory for schema handlers

*/

class eZDbSchema
{
    /**
     * Returns a shared instance of the eZDBSchemaInterface class.
     *
     * @param array|eZDBInterface|false $params If array, following key is needed:
     *        - instance: the eZDB instance (optional), if none provided, eZDB::instance() will be used.
     * @return eZDBSchemaInterface|false
     */
    static function instance( $params = false )
    {
        if ( is_object( $params ) )
        {
            $db = $params;
            $params = array( 'instance' => $db );
        }

        if ( !isset( $params['instance'] ) )
        {
            $db = eZDB::instance();
            $params['instance'] = $db;
        }

        $db = $params['instance'];

        if ( !isset( $params['type'] ) )
            $params['type'] = $db->databaseName();
        if ( !isset( $params['schema'] ) )
            $params['schema'] = false;

        $dbname = $params['type'];

        /* Load the database schema handler INI stuff */
        $ini = eZINI::instance( 'dbschema.ini' );
        $schemaPaths = $ini->variable( 'SchemaSettings', 'SchemaPaths' );
        $schemaHandlerClasses = $ini->variable( 'SchemaSettings', 'SchemaHandlerClasses' );

        /* Check if we have a handler */
        if ( !isset( $schemaPaths[$dbname] ) or !isset( $schemaHandlerClasses[$dbname] ) )
        {
            eZDebug::writeError( "No schema handler for database type: $dbname", __METHOD__ );
            return false;
        }

        /* Include the schema file and instantiate it */
        require_once( $schemaPaths[$dbname] );
        return new $schemaHandlerClasses[$dbname]( $params );
    }

    /*!
     \static
    */
    static function read( $filename, $returnArray = false )
    {
        $fd = @fopen( $filename, 'rb' );
        if ( $fd )
        {
            $buf = fread( $fd, 100 );
            fclose( $fd );
            if ( preg_match( '#^<\?' . "php#", $buf ) )
            {
                include( $filename );
                if ( $returnArray )
                {
                    $params = array();
                    if ( isset( $schema ) )
                        $params['schema'] = $schema;
                    if ( isset( $data ) )
                        $params['data'] = $data;
                    return $params;
                }
                else
                {
                    return $schema;
                }
            }
            else if ( preg_match( '#a:[0-9]+:{#', $buf ) )
            {
                return unserialize( file_get_contents( $filename ) );
            }
            else
            {
                eZDebug::writeError( "Unknown format for file $filename" );
                return false;
            }
        }
        return false;
    }

    /*!
     \static
    */
    static function readArray( $filename )
    {
        $schema = false;
        include( $filename );
        return $schema;
    }

    /*!
     \static
    */
    static function generateUpgradeFile( $differences )
    {
        $diff = var_export( $differences, true );
        return ( "<?php \n\$diff = \n" . $diff . ";\nreturn \$diff;\n?>\n" );
    }

    /*!
     \static
    */
    static function writeUpgradeFile( $differences, $filename )
    {
        $fp = @fopen( $filename, 'w' );
        if ( $fp )
        {
            fputs( $fp, eZDbSchema::generateUpgradeFile( $differences ) );
            fclose( $fp );
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Merges 2 db schemas, basically appending 2nd on top of 1st
     * @return array the merged schema
     */
    static function merge( $schema1, $schema2 )
    {
        $merged = $schema1;
        foreach( $schema2 as $tablename => $tabledef )
        {
            if ( $tablename != '_info' )
            {
                $merged[$tablename] = $tabledef;
            }
        }
       return $merged;
    }
}
?>
