<?php
//
// Definition of eZContentClassGroup class
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
//! The class eZContentClassGroup
/*!

*/

class eZContentClassGroup extends eZPersistentObject
{
    function eZContentClassGroup( $row )
    {
       $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "creator_id" => array( 'name' => "CreatorID",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true,
                                                                'foreign_class' => 'eZUser',
                                                                'foreign_attribute' => 'contentobject_id',
                                                                'multiplicity' => '1..*' ),
                                         "modifier_id" => array( 'name' => "ModifierID",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true,
                                                                 'foreign_class' => 'eZUser',
                                                                 'foreign_attribute' => 'contentobject_id',
                                                                 'multiplicity' => '1..*' ),
                                         "created" => array( 'name' => "Created",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "modified" => array( 'name' => "Modified",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      "keys" => array( "id" ),
                      'function_attributes' => array( 'modifier' => 'modifier',
                                                      'creator' => 'creator' ),
                      "increment_key" => "id",
                      "class_name" => "eZContentClassGroup",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentclassgroup" );
    }

    static function create( $userID = false )
    {
        $dateTime = time();
        if ( !$userID )
            $userID = eZUser::currentUserID();
        $row = array(
            "id" => null,
            "name" => "",
            "creator_id" => $userID,
            "modifier_id" => $userID,
            "created" => $dateTime,
            "modified" => $dateTime );
        return new eZContentClassGroup( $row );
    }

    function modifier()
    {
        if ( isset( $this->ModifierID ) and $this->ModifierID )
        {
            return eZUser::fetch( $this->ModifierID );
        }
        return null;
    }

    function creator()
    {
        if ( isset( $this->CreatorID ) and $this->CreatorID )
        {
            return eZUser::fetch( $this->CreatorID );
        }
        return null;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeSelected( $id )
    {
        eZPersistentObject::removeObject( eZContentClassGroup::definition(),
                                          array( "id" => $id ) );
    }

    /*!
     Fetch Class group by name, and return first result.

     \param name
     \param asObject
    */
    static function fetchByName( $name, $asObject = true )
    {
        $conds = array( 'name' => $name );
        return eZPersistentObject::fetchObject( eZContentClassGroup::definition(),
                                                null,
                                                $conds,
                                                $asObject );
    }

    static function fetch( $id, $user_id = false, $asObject = true )
    {
        $conds = array( "id" => $id );
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        return eZPersistentObject::fetchObject( eZContentClassGroup::definition(),
                                                null,
                                                $conds,
                                                $asObject );
    }

    static function fetchList( $user_id = false, $asObject = true )
    {
        $conds = array();
        if ( $user_id !== false and is_numeric( $user_id ) )
            $conds["creator_id"] = $user_id;
        return eZPersistentObject::fetchObjectList( eZContentClassGroup::definition(),
                                                    null, $conds, null, null,
                                                    $asObject );
    }

    /*!
     Appends the class \a $class to this group.
     \param $class Can either be an eZContentClass object or a class ID.
     \return the class group link object.
     \note tranaction unsafe.
    */
    function appendClass( $class, $version = false )
    {
        if ( $class instanceof eZContentClass )
        {
            $classID = $class->attribute( 'id' );
            $version = $class->attribute( 'version' );
        }
        else
        {
            $classID = $class;
        }
        $classGroupLink = eZContentClassClassGroup::create( $classID,
                                                            $version,
                                                            $this->attribute( 'id' ),
                                                            $this->attribute( 'name' ) );
        $classGroupLink->store();
        return $classGroupLink;
    }

    public $ID;
    public $Name;
    public $CreatorID;
    public $ModifierID;
    public $Created;
    public $Modified;
}

?>
