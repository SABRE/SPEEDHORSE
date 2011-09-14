<?php
//
// Definition of eZDiscountSubRule class
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
  \class eZDiscountSubRuleValue ezdiscountsubrule.php
  \brief The class eZDiscountSubRuleValue does

*/
class eZDiscountSubRuleValue extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZDiscountSubRuleValue( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "discountsubrule_id" => array( 'name' => "DiscountSubRuleID",
                                                                        'datatype' => 'integer',
                                                                        'default' => 0,
                                                                        'required' => true,
                                                                        'foreign_class' => 'eZDiscountSubRule',
                                                                        'foreign_attribute' => 'id',
                                                                        'multiplicity' => '1..*' ),
                                         "value" => array( 'name' => "Value",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         "issection" => array( 'name' => "IsSection",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      "keys" => array( "discountsubrule_id", "value", "issection" ),
                      "increment_key" => "discountsubrule_id",
                      "class_name" => "eZDiscountSubRuleValue",
                      "name" => "ezdiscountsubrule_value" );
    }

    static function fetchBySubRuleID( $discountSubRuleID, $isSection = 0, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRuleValue::definition(),
                                                    null,
                                                    array( "discountsubrule_id" => $discountSubRuleID,
                                                           "issection" => $isSection ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    static function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRuleValue::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    static function create( $discountSubRuleID, $value, $isSection = false )
    {
        $row = array( "discountsubrule_id" => $discountSubRuleID,
                      "value" => $value,
                      "issection" => $isSection );
        return new eZDiscountSubRuleValue( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeBySubRuleID ( $discountSubRuleID )
    {
        eZPersistentObject::removeObject( eZDiscountSubRuleValue::definition(),
                                          array( "discountsubrule_id" => $discountSubRuleID ) );
    }
}
?>
