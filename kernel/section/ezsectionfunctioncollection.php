<?php
//
// Definition of eZSectionFunctionCollection class
//
// Created on: <23-May-2003 16:46:17 amos>
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
  \class eZSectionFunctionCollection ezsectionfunctioncollection.php
  \brief The class eZSectionFunctionCollection does

*/

class eZSectionFunctionCollection
{
    /*!
     Constructor
    */
    function eZSectionFunctionCollection()
    {
    }

    /**
     * Fetch section object given either section id or section identifier. There should be one and only one parameter.
     * @param integer $sectionID
     * @param string $sectionIdentifier
     * @return object
     */
    function fetchSectionObject( $sectionID = false, $sectionIdentifier = false )
    {
        if( $sectionID !== false )
        {
            if( $sectionIdentifier !== false )
            {
                $sectionObject = null;
            }
            else
            {
                $sectionObject = eZSection::fetch( $sectionID );
            }
        }
        else
        {
            if( $sectionIdentifier === false )
            {
                $sectionObject = null;
            }
            else
            {
                $sectionObject = eZSection::fetchByIdentifier( $sectionIdentifier );
            }
        }
        if ( $sectionObject === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $sectionObject );
    }

    function fetchSectionList()
    {
        $sectionObjects = eZSection::fetchList( );
        return array( 'result' => $sectionObjects );
    }

    function fetchObjectList( $sectionID, $offset = false, $limit = false, $sortOrder = false, $status = false )
    {
        if ( $sortOrder === false )
        {
            $sortOrder = array( 'id' => 'desc' );
        }
        if ( $status == 'archived' )
            $status = eZContentObject::STATUS_ARCHIVED;
        else
            $status = eZContentObject::STATUS_PUBLISHED;
        $objects = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                        null,
                                                        array( 'section_id' => $sectionID,
                                                               'status' => $status ),
                                                        $sortOrder,
                                                        array( 'offset' => $offset, 'limit' => $limit ) );
        return array( 'result' => $objects );
    }

    function fetchObjectListCount( $sectionID, $status = false )
    {
        if ( $status == 'archived' )
            $status = eZContentObject::STATUS_ARCHIVED;
        else
            $status = eZContentObject::STATUS_PUBLISHED;
        $rows = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                     array(),
                                                     array( 'section_id' => $sectionID,
                                                            'status' => $status ),
                                                     false,
                                                     null,
                                                     false,
                                                     false,
                                                     array( array( 'operation' => 'count( id )',
                                                                   'name' => 'count' ) ) );
        return array( 'result' => $rows[0]['count'] );
    }

    function fetchRoles( $sectionID )
    {
        $policies = $roleIDs = $usedRoleIDs = $roles = $roleLimitations = array();

        $limitations = eZPolicyLimitation::findByType( 'Section', $sectionID, true, false );
        foreach ( $limitations as $policyEntry )
        {
            $policy = $policyEntry->policy();
            $policies[] = $policy;

            $roleID = $policy->attribute( 'role_id' );
            $roleIDs[] = $roleID;
            if ( !isset( $roleLimitations[$roleID] ) )
            {
                $roleLimitations[$roleID] = array();
            }
            $roleLimitations[$roleID][] = $policy;
        }

        foreach ( $policies as $policy )
        {
            $roleID = $policy->attribute( 'role_id' );
            if ( in_array( $roleID, $roleIDs ) && !in_array( $roleID, $usedRoleIDs ) )
            {
                $roles[] = $policy->attribute( 'role' );
                $usedRoleIDs[] = $roleID;
            }
        }

        return array( 'result' => array( 'roles' => $roles, 'limited_policies' => $roleLimitations ) );
    }

    function fetchUserRoles( $sectionID )
    {
        $userRoles = eZRole::fetchRolesByLimitation( 'section', $sectionID );
        return array( 'result' => $userRoles );
    }
}

?>
