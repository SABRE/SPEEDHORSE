<?php
//
// Definition of eZDiscountRule class
//
// Created on: <27-Nov-2002 13:05:59 wy>
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
  \class eZDiscountRule ezdiscountrule.php
  \brief The class eZDiscountRule does

*/

class eZDiscountRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZDiscountRule( $row )
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
                                                         'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZDiscountRule",
                      "name" => "ezdiscountrule" );
    }

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZDiscountRule::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    static function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountRule::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    static function create()
    {
        $row = array(
            "id" => null,
            "name" => ezpI18n::tr( "kernel/shop/discountgroup", "New discount group" ) );
        return new eZDiscountRule( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeByID( $id )
    {
        eZPersistentObject::removeObject( eZDiscountRule::definition(),
                                          array( "id" => $id ) );
    }
}
?>
