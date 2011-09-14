<?php
//
// Definition of eZProductCollectionItemOption class
//
// Created on: <10-���-2003 16:04:18 sp>
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

class eZProductCollectionItemOption extends eZPersistentObject
{
    /**
     * Initialized an eZProductCollectionItemOption object with the given
     * attribute array
     *
     * @param array $row Array of object attributes
     */
    function eZProductCollectionItemOption( $row )
    {
        $this->eZPersistentObject( $row );

    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'item_id' => array( 'name' => 'ItemID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZProductCollectionItem',
                                                             'foreign_attribute' => 'id',
                                                             'multiplicity' => '1..*' ),
                                         'option_item_id' => array( 'name' => 'OptionItemID',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true,
                                                                    'foreign_class' => 'eZProductCollectionItemOption',
                                                                    'foreign_attribute' => 'id',
                                                                    'multiplicity' => '1..*' ),
                                         'object_attribute_id' => array( 'name' => 'ObjectAttributeID',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true,
                                                                         'foreign_class' => 'eZContentObjectAttribute',
                                                                         'foreign_attribute' => 'id',
                                                                         'multiplicity' => '1..*' ),
                                         'name' => array( 'name' => 'Name',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         'value' => array( 'name' => 'Value',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'price' => array( 'name' => 'Price',
                                                           'datatype' => 'float',
                                                           'default' => 0,
                                                           'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZProductCollectionItemOption",
                      "name" => "ezproductcollection_item_opt" );
    }

    /**
     * Creates an eZProductCollectionItem
     *
     * @param int $productCollectionItemID
     * @param int $optionItemID
     * @param string $optionName
     * @param string $optionValue
     * @param string $optionPrice
     * @param int $attributeID
     */
    static function create( $productCollectionItemID, $optionItemID, $optionName, $optionValue, $optionPrice, $attributeID )
    {
        $row = array( 'item_id' => $productCollectionItemID,
                      'option_item_id' => $optionItemID,
                      'name' => $optionName,
                      'value' => $optionValue,
                      'price' => $optionPrice,
                      'object_attribute_id' => $attributeID );
        return new eZProductCollectionItemOption( $row );
    }

    /**
     * Clones the collection item option object and returns it.
     * The ID of the clone is reset so that the clone can be saved
     */
    function __clone()
    {
        $this->setAttribute( 'id', null );
    }

    /**
     * Copies the collection object item option. The copy will point to the
     * collection item parameter $collectionItemID.
     *
     * @param int $collectionItemID Collection item ID to match the option to
     *
     * @return eZProductCollectionItemOption The new object
     */
    function copy( $collectionItemID )
    {
        $item = clone $this;
        $item->setAttribute( 'item_id', $collectionItemID );
        $item->store();
        return $item;
    }

    /**
     * Fetches eZProductCollectionItemOption items that match the given item ID,
     * sorted by ascending order of option ID
     *
     * @param int $productCollectionItemID
     * @param bool $asObject
     *
     * @return array(eZProductCollectionItemOption)
     */
    static function fetchList( $productCollectionItemID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZProductCollectionItemOption::definition(),
                                                    null, array( "item_id" => $productCollectionItemID ),
                                                    array( "id" => "ASC"  ),
                                                    null,
                                                    $asObject );
    }

    /**
     * Removes all product collections options which are related to the
     * collection items specified in the parameter array
     *
     * @param array $itemIDList Array of eZProductCollectionItem IDs
     *
     * @return void
     */
    static function cleanupList( $itemIDList )
    {
        $db = eZDB::instance();
        $inText = $db->generateSQLINStatement( $itemIDList, 'item_id', false, false, 'int' );
        $db->query( $q = "DELETE FROM ezproductcollection_item_opt WHERE $inText" );
    }

}

?>
