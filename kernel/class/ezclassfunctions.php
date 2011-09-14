<?php
//
// Created on: <24-Sep-2004 13:20:32 jk>
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

class eZClassFunctions
{
    static function addGroup( $classID, $classVersion, $selectedGroup )
    {
        list ( $groupID, $groupName ) = explode( '/', $selectedGroup );
        $ingroup = eZContentClassClassGroup::create( $classID, $classVersion, $groupID, $groupName );
        $ingroup->store();
        return true;
    }

    static function removeGroup( $classID, $classVersion, $selectedGroup )
    {
        $class = eZContentClass::fetch( $classID, true, eZContentClass::VERSION_STATUS_DEFINED );
        if ( !$class )
            return false;
        $groups = $class->attribute( 'ingroup_list' );
        foreach ( array_keys( $groups ) as $key )
        {
            if ( in_array( $groups[$key]->attribute( 'group_id' ), $selectedGroup ) )
            {
                unset( $groups[$key] );
            }
        }

        if ( count( $groups ) == 0 )
        {
            return false;
        }
        else
        {
            foreach(  $selectedGroup as $group_id )
            {
                eZContentClassClassGroup::removeGroup( $classID, $classVersion, $group_id );
            }
        }
        return true;
    }
}

?>
