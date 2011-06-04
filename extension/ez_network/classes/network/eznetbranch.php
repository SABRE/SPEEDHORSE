<?php
//
// Definition of eZNetBranch class
//
// Created on: <03-Feb-2006 10:58:45 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Network
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
//     Att: eZ Systems AS Licensing Dept., Klostergata 30, N-3732 Skien, Norway
//
//   IMPORTANT: THE SOFTWARE IS LICENSED, NOT SOLD. ADDITIONALLY, THE
//   SOFTWARE IS LICENSED "AS IS," WITHOUT ANY WARRANTIES WHATSOEVER.
//   READ THE eZPUL BEFORE USING, INSTALLING OR MODIFYING THE SOFTWARE.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file eznetbranch.php
*/

/*!
  \class eZNetBranch eznetbranch.php
  \brief The class eZNetBranch does

*/

class eZNetBranch extends eZPersistentObject
{
    /// Consts
    const StatusDraft = 0;
    const StatusPublished = 1;

    /*!
     Constructor
    */
    function eZNetBranch($row )
    {
        $this->NetUtils = new eZNetUtils();
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => 'Name',
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         'creator_id' => array( 'name' => 'CreatorID',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "description" => array( 'name' => 'Description',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "url" => array( 'name' => 'Url',
                                                         'datatype' => 'string',
                                                         'default' => '',
                                                         'required' => true ),
                                         'status' => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true,
                                                            'keep_key' => true ) ),
                      "keys" => array( "id", 'status' ),
                      "function_attributes" => array( 'creator' => 'creator' ),
                      "increment_key" => "id",
                      "class_name" => "eZNetBranch",
                      "sort" => array( "name" => "asc" ),
                      "name" => "ezx_ezpnet_branch" );
    }

    /*!
     \static

     Fetch a list of branches based on installation remote ID.

    */
    static function fetchListByRemoteIDAndLatestID( $installationSiteID,
                                                    $latestID,
                                                    $offset = 0,
                                                    $limit = 100,
                                                    $asObject = true,
                                                    $status = eZNetBranch::StatusPublished )
    {
        $installation = eZNetInstallation::fetchBySiteID( $installationSiteID );
        if ( $installation->attribute( 'branch_id' ) == $latestID )
        {
            return array();
        }
        return eZPersistentObject::fetchObjectList( eZNetBranch::definition(),
                                                    array( 'id' ),
                                                    array( 'id' => $installation->attribute( 'branch_id' ),
                                                           'status' => $status ),
                                                    array( 'id' => 'asc' ),
                                                    array( 'limit' => $limit,
                                                           'offset' => $offset ),
                                                    $asObject );
    }

    /*!
     \static

     Fetch a list of branches based on installation remote ID.

    */
    static function fetchListByRemoteIDAndLatestModified( $installationSiteID,
                                                          $latestModified,
                                                          $offset = 0,
                                                          $limit = 100,
                                                          $asObject = true,
                                                          $status = eZNetBranch::StatusPublished )
    {
        $installation = eZNetInstallation::fetchBySiteID( $installationSiteID );
        if ( !$installation )
        {
            return false;
        }

        return eZPersistentObject::fetchObjectList( eZNetBranch::definition(),
                                                    array( 'id' ),
                                                    array( 'id' => $installation->attribute( 'branch_id' ),
                                                           'modified' => array( '>', $latestModified ),
                                                           'status' => $status ),
                                                    array( 'modified' => 'asc' ),
                                                    array( 'limit' => $limit,
                                                           'offset' => $offset ),
                                                    $asObject );
    }

    /*!
     \static

     Create branch element
    */
    static function create()
    {
        $branch = new eZNetBranch( array( 'status' => eZNetBranch::StatusDraft,
                                          'created' => time(),
                                          'creator_id' => eZUser::currentUserID() ) );
        $branch->store();

        return $branch;
    }

    /*!
     \static
    */
    static function fetch( $id,
                    $status = eZNetBranch::StatusPublished,
                    $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZNetBranch::definition(),
                                                null,
                                                array( 'id' => $id,
                                                       'status' => $status ),
                                                $asObject );
    }

    /*!
     \static

     Fetch draft

     \param Branch ID
     \param force, if force creation of draft.
     \param $asObject
    */
    static function fetchDraft( $id, $force = true, $asObject = true )
    {
        $branch = eZNetBranch::fetch( $id, eZNetBranch::StatusDraft, $asObject );
        if ( !$branch &&
             $force )
        {
            $branch = eZNetBranch::fetch( $id, eZNetBranch::StatusPublished, $asObject );
            if ( $branch )
            {
                $branch->setAttribute( 'status', eZNetBranch::StatusDraft );
                $branch->store();
            }
        }

        if ( !$branch )
        {
            return false;
        }
        return $branch;
    }

    /*!
     \reimp
    */
    function attribute( $attr, $noFunction = false )
    {
        $retVal = null;
        switch( $attr )
        {
            case 'creator':
            {
                $retVal = eZUser::fetch( $this->attribute( 'creator_id' ) );
            } break;

            default:
            {
                $retVal = eZPersistentObject::attribute( $attr );
            } break;
        }

        return $retVal;
    }

    /*!
     Publish current object
    */
    function publish()
    {
        $this->setAttribute( 'status', eZNetBranch::StatusPublished );
        $this->setAttribute( 'modified', time() );
        $this->store();
        $this->removeDraft();
    }

    /*!
     Remove draft.
    */
    function removeDraft()
    {
        $draft = eZNetBranch::fetchDraft( $this->attribute( 'id' ),
                                          false );
        if ( $draft )
        {
            $draft->remove();
        }
    }

    /*!
     \static

     Remove all objects of \a id
    */
    static function removeAll( $id )
    {
        eZPersistentObject::removeObject( eZNetBranch::definition(),
                                          array( 'id' => $id ) );
    }

    /*!
     \static

     Fetch branch list
    */
    static function fetchList( $offset = 0,
                        $limit = 20,
                        $status = self::StatusPublished,
                        $asObject = true,
                        array $sort = array( 'id' => 'asc' ) )
    {
        return eZPersistentObject::fetchObjectList( self::definition(),
                                                    null,
                                                    array( 'status' => $status ),
                                                    $sort,
                                                    array( 'limit' => $limit,
                                                           'offset' => $offset ),
                                                    $asObject );
    }

    /**
     * Fetch branch list count
     *
     * @param int $status
     * @return int
     */
    static function fetchListCount( $status = self::StatusPublished )
    {
        return eZPersistentObject::count( self::definition(), array( 'status' => $status ) );
    }
}
?>
