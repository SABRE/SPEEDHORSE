<?php
//
// Definition of eZMatrixDefinition class
//
// Created on: <03-Jun-2003 18:30:44 sp>
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
  \class eZMatrixDefinition ezmatrixdefinition.php
  \ingroup eZDatatype
  \brief The class eZMatrixDefinition does

*/

class eZMatrixDefinition
{
    /*!
     Constructor
    */
    function eZMatrixDefinition()
    {
        $this->ColumnNames = array();
    }


    function decodeClassAttribute( $xmlString )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        if ( strlen ( $xmlString ) != 0 )
        {
            $success = $dom->loadXML( $xmlString );
            $columns = $dom->getElementsByTagName( "column-name" );
            $columnList = array();
            foreach ( $columns as $columnElement )
            {
                $columnList[] = array( 'name' => $columnElement->textContent,
                                       'identifier' => $columnElement->getAttribute( 'id' ),
                                       'index' =>  $columnElement->getAttribute( 'idx' ) );
            }
            $this->ColumnNames = $columnList;
        }
        else
        {
            $this->addColumn( );
            $this->addColumn( );
        }

    }

    function attributes()
    {
        return array( 'columns' );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        if ( $attr == 'columns' )
        {
            return $this->ColumnNames;
        }

        eZDebug::writeError( "Attribute '$attr' does not exist", __METHOD__ );
        return null;
    }

    function xmlString( )
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( "ezmatrix" );
        $doc->appendChild( $root );

        foreach ( $this->ColumnNames as $columnName )
        {
            $columnNameNode = $doc->createElement( 'column-name' );
            $columnNameNode->appendChild( $doc->createTextNode( $columnName['name'] ) );
            $columnNameNode->setAttribute( 'id', $columnName['identifier'] );
            $columnNameNode->setAttribute( 'idx', $columnName['index'] );
            $root->appendChild( $columnNameNode );
            unset( $columnNameNode );
            unset( $textNode );
        }

        $xml = $doc->saveXML();

        return $xml;
    }

    function addColumn( $name = false , $id = false )
    {
        if ( $name == false )
        {
            $name = 'Col_' . ( count( $this->ColumnNames ) );
        }

        if ( $id == false )
        {
            // Initialize transformation system
            $trans = eZCharTransform::instance();
            $id = $trans->transformByGroup( $name, 'identifier' );
        }

        $this->ColumnNames[] = array( 'name' => $name,
                                      'identifier' => $id,
                                      'index' => count( $this->ColumnNames ) );
    }

    function removeColumn( $index )
    {
        if ( $index == 0 && count( $this->ColumnNames ) == 1 )
        {
            $this->ColumnNames = array();
        }
        else
        {
            unset( $this->ColumnNames[$index] );
        }
    }

    public $ColumnNames;

}

?>
