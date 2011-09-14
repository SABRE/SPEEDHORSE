<?php
//
// Created on: <02-Oct-2006 13:37:23 dl>
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

class eZContentClassName extends eZPersistentObject
{
    function eZContentClassName( $row )
    {
        eZPersistentObject::eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'contentclass_id' => array( 'name' => 'ContentClassID',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZContentClass',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
                                         'contentclass_version' => array( 'name' => 'ContentClassVersion',
                                                                          'datatype' => 'integer',
                                                                          'default' => 0,
                                                                          'required' => true ),
                                         'language_locale' => array( 'name' => 'LanguageLocale',
                                                                     'datatype' => 'string',
                                                                     'default' => '',
                                                                     'required' => true ),
                                         'language_id' => array( 'name' => 'LanguageID',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true,
                                                                 'foreign_class' => 'eZContentLanguage',
                                                                 'foreign_attribute' => 'id',
                                                                 'multiplicity' => '1..*' ),
                                         'name' => array( 'name' => 'Name',
                                                                    'datatype' => 'string',
                                                                    'default' => '',
                                                                    'required' => false ) ),
                      'keys' => array( 'contentclass_id',
                                       'contentclass_version',
                                       'language_locale' ),
                      'function_attributes' => array(),
                      'class_name' => 'eZContentClassName',
                      'sort' => array( 'contentclass_id' => 'asc' ),
                      'name' => 'ezcontentclass_name' );
    }

    static function fetchList( $classID, $classVersion, $languageLocaleList, $asObjects = true, $fields = null, $sorts = null, $limit = null )
    {
        $conds = array();

        if ( is_array( $languageLocaleList ) && count( $languageLocaleList ) > 0 )
            $conds[ 'language_locale'] = array( $languageLocaleList );

        $conds[ 'contentclass_id'] = $classID;
        $conds[ 'contentclass_version'] = $classVersion;

        return eZPersistentObject::fetchObjectList( eZContentClassName::definition(),
                                                            $fields,
                                                            $conds,
                                                            $sorts,
                                                            $limit,
                                                            $asObjects );
    }

    /*!
     \return the SQL where-condition for selecting the rows (with class names) in the correct language,
     i. e. in the most prioritized language from those in which an object exists.

     \param languageTable Name of the table containing the attribute with bitmaps, e.g. ezcontentclass
     \param languageListTable Name of the table containing the attribute with language id.
    */
    static function sqlFilter( $languageTable = 'ezcontentclass' )
    {
        $def = eZContentClassName::definition();
        $languageListTable = $def['name'];
        $sqlFilter = array( 'nameField' => "$languageListTable.name",
                            'from' => "$languageListTable",
                            'where' => "$languageTable.id = $languageListTable.contentclass_id AND
                                        $languageTable.version = $languageListTable.contentclass_version AND " .
                                        eZContentLanguage::sqlFilter( $languageListTable, $languageTable ),
                            'orderBy' => "$languageListTable.name" );

        return $sqlFilter;
    }

    /*!
     The same as 'sqlFilter' but adds symbol ',' to 'nameField' and 'from' parts
    */
    static function sqlAppendFilter( $languageTable = 'ezcontentclass' )
    {
        $def = eZContentClassName::definition();
        $languageListTable = $def['name'];
        $sqlFilter = array( 'nameField' => ", $languageListTable.name",
                            'from' => ", $languageListTable",
                            'where' => "AND $languageTable.id = $languageListTable.contentclass_id AND
                                        $languageTable.version = $languageListTable.contentclass_version AND " .
                                        eZContentLanguage::sqlFilter( $languageListTable, $languageTable ),
                            'orderBy' => "$languageListTable.name" );

        return $sqlFilter;
    }

    /*!
     The same as 'sqlFilter' but all fields are empty
    */
    static function sqlEmptyFilter()
    {
        return array( 'nameField' => '',
                      'from' => '',
                      'where' => '',
                      'orderBy' => '' );
    }

    static function removeClassName( $contentClassID, $contentClassVersion )
    {
        $db = eZDb::instance();
        $db->begin();

        $sql = "DELETE FROM ezcontentclass_name WHERE contentclass_id = $contentClassID AND contentclass_version = $contentClassVersion";
        $db->query( $sql );

        $db->commit();
    }

}

?>
