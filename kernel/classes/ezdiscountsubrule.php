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
  \class eZDiscountSubRule ezdiscountsubrule.php
  \brief The class eZDiscountSubRule does

*/

class eZDiscountSubRule extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZDiscountSubRule( $row )
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
                                         "discountrule_id" => array( 'name' => "DiscountRuleID",
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZDiscountRule',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
                                         "discount_percent" => array( 'name' => "DiscountPercent",
                                                                      'datatype' => 'float',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "limitation" => array( 'name' => "Limitation",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZDiscountSubRule",
                      "name" => "ezdiscountsubrule" );
    }

    function setAttribute( $attr, $val )
    {
        switch( $attr )
        {
            case 'discount_percent':
            {
                $locale = eZLocale::instance();

                $val = $locale->internalNumber( $val );
                if ( $val < 0.0 )
                    $val = 0.0;
                if ( $val > 100.0 )
                    $val = 100.0;
                eZPersistentObject::setAttribute( $attr, $val );
            } break;

            default:
            {
                eZPersistentObject::setAttribute( $attr, $val );
            } break;
        }
    }

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZDiscountSubRule::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    static function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRule::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    static function fetchByRuleID( $discountRuleID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZDiscountSubRule::definition(),
                                                    null,
                                                    array( "discountrule_id" => $discountRuleID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    static function create( $discountRuleID )
    {
        $row = array(
            "id" => null,
            "name" => ezpI18n::tr( 'kernel/shop/discountgroup', "New Discount Rule" ),
            "discountrule_id" => $discountRuleID,
            "discount_percent" => "",
            "limitation" => "*" );
        return new eZDiscountSubRule( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function remove ( $id = null, $dumb = null )
    {
        eZPersistentObject::removeObject( eZDiscountSubRule::definition(),
                                          array( "id" => $id ) );
    }
}
?>
