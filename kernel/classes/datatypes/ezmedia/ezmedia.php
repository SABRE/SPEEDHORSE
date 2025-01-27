<?php
//
// Definition of eZBinaryFile class
//
// Created on: <30-Apr-2002 16:47:08 bf>
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
  \class eZMedia ezmedia.php
  \ingroup eZDatatype
  \brief The class eZMedia handles registered media files

*/

class eZMedia extends eZPersistentObject
{
    function eZMedia( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        static $definition = array( "fields" => array( "contentobject_attribute_id" => array( 'name' => "ContentObjectAttributeID",
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true,
                                                                                'foreign_class' => 'eZContentObjectAttribute',
                                                                                'foreign_attribute' => 'id',
                                                                                'multiplicity' => '1..*' ),
                                         "version" => array( 'name' => "Version",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "filename" => array( 'name' => "Filename",
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         "original_filename" => array( 'name' => "OriginalFilename",
                                                                       'datatype' => 'string',
                                                                       'default' => '',
                                                                       'required' => true ),
                                         "mime_type" => array( 'name' => "MimeType",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         "width" => array( 'name' => "Width",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         "height" => array( 'name' => "Height",
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         "has_controller" => array( 'name' => "HasController",
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                         "controls" => array( 'name' => "Controls",
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         "is_autoplay" => array( 'name' => "IsAutoplay",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         "pluginspage" => array( 'name' => "Pluginspage",
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "quality" => array( 'name' => 'Quality',
                                                             'datatype' => 'string',
                                                             'default' => '',
                                                             'required' => true ),
                                         "is_loop" => array( 'name' => "IsLoop",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ) ),
                      "keys" => array( "contentobject_attribute_id", "version" ),
                      'function_attributes' => array( 'filesize' => 'filesize',
                                                      'filepath' => 'filepath',
                                                      'mime_type_category' => 'mimeTypeCategory',
                                                      'mime_type_part' => 'mimeTypePart' ),
                      "relations" => array( "contentobject_attribute_id" => array( "class" => "ezcontentobjectattribute",
                                                                                   "field" => "id" ),
                                            "version" => array( "class" => "ezcontentobjectattribute",
                                                                "field" => "version" )),
                      "class_name" => "eZMedia",
                      "name" => "ezmedia" );
        return $definition;
    }

    function fileSize()
    {
        $fileInfo = $this->storedFileInfo();

        $file = eZClusterFileHandler::instance( $fileInfo['filepath'] );

        if ( $file->exists() )
        {
            return $file->size();
        }

        return 0;
    }

    function filePath()
    {
        $fileInfo = $this->storedFileInfo();
        return $fileInfo['filepath'];
    }

    function mimeTypeCategory()
    {
        $types = explode( "/", eZPersistentObject::attribute( "mime_type" ) );
        return $types[0];
    }

    function mimeTypePart()
    {
        $types = explode( "/", eZPersistentObject::attribute( "mime_type" ) );
        return $types[1];
    }

    static function create( $contentObjectAttributeID, $version )
    {
        $row = array( "contentobject_attribute_id" => $contentObjectAttributeID,
                      "version" => $version,
                      "filename" => "",
                      "original_filename" => "",
                      "mime_type" => "",
                      "width" => "0",
                      "height" => "0",
                      "controller" => true,
                      "autoplay" => true,
                      "pluginspage" => "",
                      "is_loop" => false,
                      "quality" => "",
                      "controls" => ""
                      );
        return new eZMedia( $row );
    }

    static function fetch( $id, $version, $asObject = true )
    {
        if( $version == null )
        {
            return eZPersistentObject::fetchObjectList( eZMedia::definition(),
                                                        null,
                                                        array( "contentobject_attribute_id" => $id ),
                                                        null,
                                                        null,
                                                        $asObject );
        }
        else
        {
            return eZPersistentObject::fetchObject( eZMedia::definition(),
                                                    null,
                                                    array( "contentobject_attribute_id" => $id,
                                                           "version" => $version ),
                                                    $asObject );
        }
    }

    static function fetchByFileName( $filename, $version = null, $asObject = true )
    {
        if ( $version == null )
        {
            return eZPersistentObject::fetchObjectList( eZMedia::definition(),
                                                        null,
                                                        array( 'filename' => $filename ),
                                                        null,
                                                        null,
                                                        $asObject );
        }
        else
        {
            return eZPersistentObject::fetchObject( eZMedia::definition(),
                                                    null,
                                                    array( 'filename' => $filename,
                                                           'version' => $version ),
                                                    $asObject );
        }
    }

    /**
     * Fetch media objects by content object id
     * @param int $contentObjectID contentobject id
     * @param string $languageCode language code
     * @param boolean $asObject if return object
     * @return array
     */
    static function fetchByContentObjectID( $contentObjectID, $languageCode = null, $asObject = true )
    {
        $condition = array();
        $condition['contentobject_id'] = $contentObjectID;
        $condition['data_type_string'] = 'ezmedia';
        if ( $languageCode != null )
        {
            $condition['language_code'] = $languageCode;
        }
        $custom = array( array( 'operation' => 'DISTINCT id',
                             'name' => 'id' ) );
        $ids = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                             array(),
                                             $condition,
                                             null,
                                             null,
                                             false,
                                             false,
                                             $custom );
        $mediaFiles = array();
        foreach ( $ids as $id )
        {
            $mediaFileObjectAttribute = eZMedia::fetch( $id['id'], null, $asObject );
            $mediaFiles = array_merge( $mediaFiles, $mediaFileObjectAttribute );
        }
        return $mediaFiles;
    }

    static function removeByID( $id, $version )
    {
        if( $version == null )
        {
            eZPersistentObject::removeObject( eZMedia::definition(),
                                              array( "contentobject_attribute_id" => $id ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZMedia::definition(),
                                              array( "contentobject_attribute_id" => $id,
                                                     "version" => $version ) );
        }
    }

    function storedFileInfo()
    {
        $fileName = $this->attribute( 'filename' );
        $mimeType = $this->attribute( 'mime_type' );
        $originalFileName = $this->attribute( 'original_filename' );

        $storageDir = eZSys::storageDirectory();

        $group = '';
        $type = '';
        if ( $mimeType )
            list( $group, $type ) = explode( '/', $mimeType );

        $filePath = $storageDir . '/original/' . $group . '/' . $fileName;

        return array( 'filename' => $fileName,
                      'original_filename' => $originalFileName,
                      'filepath' => $filePath,
                      'mime_type' => $mimeType );
    }

    public $ContentObjectAttributeID;
    public $Filename;
    public $OriginalFilename;
    public $MimeType;
    public $Width;
    public $Height;
    public $HasController;
    public $Controls;
    public $IsLoop;
    public $IsAutoplay;
    public $Pluginspage;
    public $Quality;
}

?>
