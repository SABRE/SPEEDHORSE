<?php
//
// Definition of eZViewCounter class
//
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

//!! eZKernel
//! The class eZViewCounter
/*!

*/

class eZViewCounter extends eZPersistentObject
{
    function eZViewCounter( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "node_id" => array( 'name' => "NodeID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZContentObjectTreeNode',
                                                             'foreign_attribute' => 'node_id',
                                                             'multiplicity' => '1..*' ),
                                         "count" => array( 'name' => "Count",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ) ),
                      "keys" => array( "node_id" ),
                      'relations' => array( 'node_id' => array( 'class' => 'eZContentObjectTreeNode',
                                                                'field' => 'node_id' ) ),
                      "class_name" => "eZViewCounter",
                      "sort" => array( "count" => "desc" ),
                      "name" => "ezview_counter" );
    }

    static function create( $node_id )
    {
        $row = array("node_id" => $node_id,
                     "count" => 0 );
        return new eZViewCounter( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    public static function removeCounter( $node_id )
    {
        eZPersistentObject::removeObject( eZViewCounter::definition(),
                                          array("node_id" => $node_id ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function clear( $node_id )
    {
        $counter = eZViewCounter::fetch( $node_id );
        if ( $counter != null )
        {
            $counter->setAttribute( 'count', 0 );
            $counter->store();
        }
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function increase()
    {
        $currentCount = $this->attribute( 'count' );
        $newCount = $currentCount + 1;
        $this->setAttribute( 'count', $newCount );
        $this->store();
    }

    static function fetch( $node_id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZViewCounter::definition(),
                                                null,
                                                array("node_id" => $node_id ),
                                                $asObject );
    }

    static function fetchTopList( $classID = false, $sectionID = false, $offset = false, $limit = false )
    {
        if ( !$classID && !$sectionID )
        {

            return  eZPersistentObject::fetchObjectList( eZViewCounter::definition(),
                                                         null,
                                                         null,
                                                         null,
                                                         array( 'length' => $limit, 'offset' => $offset ),
                                                         false );
        }

        $queryPart = "";
        if ( $classID != false )
        {
            $classID = (int)$classID;
            $queryPart .= "ezcontentobject.contentclass_id=$classID AND ";
        }

        if ( $sectionID != false )
        {
            $sectionID = (int)$sectionID;
            $queryPart .= "ezcontentobject.section_id=$sectionID AND ";
        }

        $db = eZDB::instance();
        $query = "SELECT ezview_counter.*
                  FROM
                         ezcontentobject_tree,
                         ezcontentobject,
                         ezview_counter
                  WHERE
                         ezview_counter.node_id=ezcontentobject_tree.node_id AND
                         $queryPart
                         ezcontentobject_tree.contentobject_id=ezcontentobject.id
                  ORDER BY ezview_counter.count DESC";

        if ( !$offset && !$limit )
        {
            $countListArray = $db->arrayQuery( $query );
        }
        else
        {
            $countListArray = $db->arrayQuery( $query, array( "offset" => $offset,
                                                               "limit" => $limit ) );
        }
        return $countListArray;
    }

    /// \privatesection
    public $NodeID;
    public $Count;
}

?>
