<?php
//
// Definition of eZAuthor class
//
// Created on: <19-Aug-2002 10:52:01 bf>
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
  \class eZAuthor ezauthor.php
  \ingroup eZDatatype
  \brief eZAuthor handles author lists

  \code

  $author = new eZAuthor( "Colour" );
  $author->addValue( "Red" );
  $author->addValue( "Green" );

  // Serialize the class to an XML document
  $xmlString = $author->xmlString();

  \endcode
*/

class eZAuthor
{
    function eZAuthor( )
    {
        $this->Authors = array();
        $this->AuthorCount = 0;
    }

    /*!
     Sets the name of the author set.
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
     Returns the name of the author set.
    */
    function name()
    {
        return $this->Name;
    }

    /**
     * Add an author
     *
     * @param int $id
     * @param string $name
     * @param string $email
     */
    function addAuthor( $id, $name, $email )
    {
        if ( $id == -1 )
            $id = $this->Authors[$this->AuthorCount - 1]['id'] + 1;

        $this->Authors[] = array( "id" => $id,
                                  "name" => $name,
                                  "email" => $email,
                             "is_default" => false );

        $this->AuthorCount ++;
    }

    /**
     * Remove authors
     *
     * @param array $removeList List of id's of authors to remove
     */
    function removeAuthors( $removeList )
    {
        if ( count( $removeList ) > 0 )
            foreach ( $removeList as $id )
            {
                foreach ( $this->Authors as $authorKey => $author )
                {
                    if ( $author['id'] == $id )
                    {
                        array_splice( $this->Authors, $authorKey, 1 );
                        $this->AuthorCount --;
                    }
                }
            }
    }

    function attributes()
    {
        static $def = array( 'author_list',
                             'name',
                             'is_empty' );
        return $def;
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            }break;
            case "is_empty" :
            {
                return $this->AuthorCount === 0;
            }break;
            case "author_list" :
            {
                return $this->Authors;
            }break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", __METHOD__ );
                return null;
            }
            break;
        }
    }

    /*!
     \return a string which contains all the interesting meta data.

     The result of this method can passed to the search engine or other
     parts which work on meta data.

     The string will contain all the authors with their name and email.

     Example:
     \code
     'John Doe john@doe.com'
     \endcode
    */
    function metaData()
    {
        $data = '';
        foreach ( $this->Authors as $author )
        {
            $data .= $author['name'] . ' ' . $author['email'] . "\n";
        }
        return $data;
    }

    /*!
     Will decode an xml string and initialize the eZ author object
    */
    function decodeXML( $xmlString )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom->loadXML( $xmlString );

        if ( $success )
        {
            $authors = $dom->getElementsByTagName( 'author' );
            foreach ( $authors as $author )
            {
                $this->addAuthor( $author->getAttribute( "id" ), $author->getAttribute( "name" ), $author->getAttribute( "email" ) );
            }
        }
    }

    /*!
     Will return the XML string for this author set.
    */
    function xmlString( )
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );

        $root = $doc->createElement( "ezauthor" );
        $doc->appendChild( $root );

        $authors = $doc->createElement( "authors" );
        $root->appendChild( $authors );

        $id = 0;
        foreach ( $this->Authors as $author )
        {
            unset( $authorNode );
            $authorNode = $doc->createElement( "author" );
            $authorNode->setAttribute( "id", $id++ );
            $authorNode->setAttribute( "name", $author["name"] );
            $authorNode->setAttribute( "email", $author["email"] );

            $authors->appendChild( $authorNode );
        }

        $xml = $doc->saveXML();

        return $xml;
    }

    /// Contains the Authors.
    protected $Authors;

    /// Contains the author counter value.
    protected $AuthorCount;

    // Contains the name of the author set.
    protected $Name;
}

?>
