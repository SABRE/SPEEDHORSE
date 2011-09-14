<?php
//
// Definition of eZTipafriendCounter class
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
//! The class eZTipafriendCounter
/*!

*/

class eZTipafriendCounter extends eZPersistentObject
{
    function eZTipafriendCounter( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'node_id' => array( 'name' => 'NodeID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZContentObjectTreeNode',
                                                             'foreign_attribute' => 'node_id',
                                                             'multiplicity' => '1..*' ),
                                         'count' => array( 'name' => 'Count', // deprecated column, must not be used
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'requested' => array( 'name' => 'Requested',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      'keys' => array( 'node_id', 'requested' ),
                      'relations' => array( 'node_id' => array( 'class' => 'eZContentObjectTreeNode',
                                                                'field' => 'node_id' ) ),
                      'class_name' => 'eZTipafriendCounter',
                      'sort' => array( 'count' => 'desc' ),
                      'name' => 'eztipafriend_counter' );
    }

    static function create( $nodeID )
    {
        return new eZTipafriendCounter( array( 'node_id' => $nodeID,
                                               'count' => 0,
                                               'requested' => time() ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeForNode( $nodeID )
    {
        eZPersistentObject::removeObject( eZTipafriendCounter::definition(),
                                          array( 'node_id' => $nodeID ) );
    }

    /*!
     \deprecated
     Use removeForNode instead
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function clear( $nodeID )
    {
        eZTipafriendCounter::removeForNode( $nodeID );
    }

    /*!
     \deprecated, will be removed in future versions of eZP
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function increase()
    {
    }

    static function fetch( $nodeID, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZTipafriendCounter::definition(),
                                                null,
                                                array( 'node_id' => $nodeID ),
                                                $asObject );
    }

    /*!
     \static
     Removes all counters for tipafriend functionality.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM eztipafriend_counter" );
    }

    /// \privatesection
    public $NodeID;
    public $Count;
}

?>
