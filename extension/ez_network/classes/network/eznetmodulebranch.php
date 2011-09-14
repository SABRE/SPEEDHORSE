<?php
//
// Definition of eZNetModuleBranch class
//
// Created on: <26-Sep-2006 11:43:53 hovik>
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

/*! \file eznetmodulebranch.php
*/

/*!
  \class eZNetModuleBranch eznetmodulebranch.php
  \brief The class eZNetModuleBranch does

*/


class eZNetModuleBranch extends eZPersistentObject
{
    /// Consts
    const StatusDraft = 0;
    const StatusPublished = 1;


    /*!
     Constructor
    */
    function eZNetModuleBranch($row )
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
                                         'version_identifier' => array( 'name' => 'VersionIdentifier',
                                                                        'datatype' => 'string',
                                                                        'default' => '',
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
                                                            'keep_key' => true ),
                                         'is_certified' => array( 'name' => 'IsCertified' ,
                                                                 'datatype' => 'boolean',
                                                                 'default' => true,
                                                                 'required' => false ) ),
                      "keys" => array( "id", 'status' ),
                      "function_attributes" => array( 'creator' => 'creator',
                                                      'version_value' => 'versionValue',
                                                      'possible_dependencies' => 'possibleDependencies',
                                                      'uncertified_dependencies' => 'fetchUncertifiedDependencies' ),
                      "increment_key" => "id",
                      "class_name" => "eZNetModuleBranch",
                      "sort" => array( "name" => "asc" ),
                      "name" => "ezx_ezpnet_module_branch" );
    }

    /*!
     \static
     Fetch eZNetModuleBranch list by installation site ID

     \param installation site ID
     \param asObject

     \return eZNetModuleBranch list
    */
    static function fetchListBySiteID( $siteID,
                                       $asObject = true )
    {
        $moduleInstallationList = eZNetModuleInstallation::fetchListBySiteID( $siteID );
        $moduleBranchList = array();

        foreach( $moduleInstallationList as $moduleInstallation )
        {
            $moduleBranchList[] = $moduleInstallation->attribute( 'module_branch' );
        }

        return $moduleBranchList;
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
                                                          $status = eZNetModuleBranch::StatusPublished )
    {
        $moduleList = eZNetModuleInstallation::fetchListBySiteID( $installationSiteID );
        $moduleBranchIDList = array();
        foreach( $moduleList as $module )
        {
            $moduleBranchIDList[] = $module->attribute( 'module_branch_id' );
        }

        return eZNetModuleBranch::fetchObjectList( eZNetModuleBranch::definition(),
                                                   array( 'id' ),
                                                   array( 'id' => array( $moduleBranchIDList ),
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
        $branch = new eZNetModuleBranch( array( 'status' => eZNetModuleBranch::StatusDraft,
                                                'created' => time(),
                                                'creator_id' => eZUser::currentUserID() ) );
        $branch->store();

        return $branch;
    }

    /*!
     \static
    */
    static function fetch( $id,
                           $status = eZNetModuleBranch::StatusPublished,
                           $asObject = true )
    {
        return eZNetModuleBranch::fetchObject( eZNetModuleBranch::definition(),
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
        $branch = eZNetModuleBranch::fetch( $id, eZNetModuleBranch::StatusDraft, $asObject );
        if ( !$branch &&
             $force )
        {
            $branch = eZNetModuleBranch::fetch( $id, eZNetModuleBranch::StatusPublished, $asObject );
            if ( $branch )
            {
                $branch->setAttribute( 'status', eZNetModuleBranch::StatusDraft );
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
            case 'version_value':
            {
                // First check extension for version information
                $extensionInfo = eZNetUtils::extensionInfo( $this->attribute( 'version_identifier' ) );
                if ( $extensionInfo !== null )
                {
                    $retVal = $extensionInfo['version'];
                }
                // If it does not exsist, check extension for information.
                else
                {
                    $db = eZDB::instance();
                    $sql = 'SELECT value FROM ezsite_data WHERE name=\'' . $db->escapeString( $this->attribute( 'version_identifier' ) ) . '\'';
                    $result = $db->arrayQuery( $sql );
                    if ( count( $result ) )
                    {
                        $retVal = $result[0]['value'];
                    }
                }
            } break;

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
        $this->setAttribute( 'status', eZNetModuleBranch::StatusPublished );
        $this->setAttribute( 'modified', time() );
        $this->store();
        $this->removeDraft();
    }

    /*!
     Remove draft.
    */
    function removeDraft()
    {
        $draft = eZNetModuleBranch::fetchDraft( $this->attribute( 'id' ),
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
        eZNetModuleBranch::removeObject( eZNetModuleBranch::definition(),
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
        return eZNetModuleBranch::fetchObjectList( self::definition(),
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

    /**
     * Fetches the eZNetModuleBranch items the current module branch depends on
     * @return array( eZNetModuleBranchDependency )
    */
    public function fetchUncertifiedDependencies()
    {
        return eZNetModuleBranchDependency::fetchUncertifiedDependencies( $this );
    }

    /**
     * Fetches the eZNetModuleBranch objects that the current module can depend on
     * This excludes branches of the same module
     */
    public function possibleDependencies()
    {
        return eZNetModuleBranch::fetchObjectList(
            eZNetModuleBranch::definition(),
            null,
            array(
                'version_identifier' => array( '<>', $this->attribute( 'version_identifier' ) ),
                'status' => eZNetModuleBranch::StatusPublished
            )
        );
    }

    /*
     Fetches the array of this module dependencies IDs
    */
    public function dependenciesIDArray()
    {
        $return = array();

        $list = eZNetModuleBranchDependency::fetchDependsModulesList( $this, $asObject = false );
        eZDebug::writeDebug( $list, __METHOD__ );
        foreach( $list as $item )
        {
            $return[] = $item['depends_on_module_branch_id'];
        }
        return $return;
    }
}

?>
