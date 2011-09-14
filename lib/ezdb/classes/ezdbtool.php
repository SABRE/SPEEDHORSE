<?php
//
// Definition of eZDBTool class
//
// Created on: <11-Dec-2002 15:07:25 amos>
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
  \class eZDBTool ezdbtool.php
  \brief The class eZDBTool does

*/

class eZDBTool
{
    /*!
     \return true if the database does not contain any relation objects.
     \note If db is not specified it will use eZDB::instance()
    */
    static function isEmpty( $db )
    {
        if ( $db === null )
            $db = eZDB::instance();
        $relationTypeMask = $db->supportedRelationTypeMask();
        $count = $db->relationCounts( $relationTypeMask );
        return $count == 0;
    }

    /*!
     Tries to remove all relation types from the database.
     \note If db is not specified it will use eZDB::instance()
    */
    static function cleanup( $db )
    {
        if ( $db === null )
            $db = eZDB::instance();
        $relationTypes = $db->supportedRelationTypes();
        $result = true;
        $defaultRegexp = "#^ez|tmp_notification_rule_s#";
        foreach ( $relationTypes as $relationType )
        {
            $relationItems = $db->relationList( $relationType );
            // This is the default regexp, unless the db driver provides one
            $matchRegexp = null;
            if ( method_exists( $db, 'relationMatchRegexp' ) )
            {
                $matchRegexp = $db->relationMatchRegexp( $relationType );
            }
            if ( $matchRegexp === null )
                $matchRegexp = $defaultRegexp;
            foreach ( $relationItems as $relationItem )
            {
                // skip relations that shouldn't be touched
                if ( $matchRegexp !== false and
                     !preg_match( $matchRegexp, $relationItem ) )
                    continue;

                if ( !$db->removeRelation( $relationItem, $relationType ) )
                {
                    $result = false;
                    break;
                }
            }
            if ( !$result )
                break;
        }
        return $result;
    }
}

?>
