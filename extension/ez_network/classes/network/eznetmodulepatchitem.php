<?php
//
// Definition of eZNetModulePatchItem class
//
// Created on: <03-Oct-2006 21:17:00 hovik>
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

/*! \file eznetmodulepatchitem.php
*/

/*!
  \class eZNetModulePatchItem eznetmodulepatchitem.php
  \brief The class eZNetModulePatchItem does

*/


class eZNetModulePatchItem extends eZNetPatchItemBase
{
    /*!
     Constructor
    */
    function eZNetModulePatchItem( $row )
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
                                         "installation_id" => array( 'name' => 'InstallationID',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZNetInstallation',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
                                         'node_id' => array( 'name' => 'NodeID',
                                                             'datatype' => 'string',
                                                             'default' => '',
                                                             'required' => false ),
                                         "module_patch_id" => array( 'name' => 'ModulePatchID',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true,
                                                                     'foreign_class' => 'eZNetModulePatch',
                                                                     'foreign_attribute' => 'id',
                                                                     'multiplicity' => '1..*' ),
                                         "status" => array( 'name' => 'Status',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         "fmode" => array( 'name' => 'Mode',
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         "modified" => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "started" => array( 'name' => 'Started',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "finnished" => array( 'name' => 'Finnished',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( 'module_patch' => 'modulePatch',
                                                      'branch_id' => 'branchID',
                                                      'patch' => 'patch',
                                                      'patch_exists' => 'patchExists',
                                                      'node_name' => 'nodeName',
                                                      'required_patch_item' => 'requiredPatchItem',
                                                      'installation' => 'installation' ),
                      "increment_key" => "id",
                      "class_name" => "eZNetModulePatchItem",
                      "sort" => array( "module_patch_id" => "desc" ),
                      "name" => "ezx_ezpnet_mod_patch_item" );
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
                                                          $status = eZNetInstallation::StatusPublished )
    {
        $installation = eZNetInstallation::fetchBySiteID( $installationSiteID );
        if ( !$installation )
        {
            return false;
        }

        return eZNetModulePatchItem::fetchObjectList( eZNetModulePatchItem::definition(),
                                                      array( 'id' ),
                                                      array( 'installation_id' => $installation->attribute( 'id' ),
                                                             'modified' => array( '>', $latestModified ) ),
                                                      array( 'modified' => 'asc' ),
                                                      array( 'limit' => $limit,
                                                             'offset' => $offset ),
                                                      $asObject );
    }

    /*!
     \static
     Fetch latest installed patch.

    \param moduleBranchID
    \param $asObject
    */
    static function fetchLatestInstalled( $moduleBranchID,
                                          $asObject = true )
    {
        $modulePatchIDList = array();
        $modulePatchList = eZNetModulePatch::fetchListByBranchID( $moduleBranchID );
        $modulePatchList = !$modulePatchList ? array() : $modulePatchList;
        foreach ( $modulePatchList as $modulePatch )
        {
            $modulePatchIDList[] = $modulePatch->attribute( 'id' );
        }

        if ( !count( $modulePatchIDList ) )
        {
            return null;
        }

        $modulePatchIDListStr = implode( ',', $modulePatchIDList );

        $nodeIDPart = '';
        $nodeID = eZNetUtils::nodeID();
        if ( $nodeID == '' )
        {
            $nodeIDPart = "       AND ( ITEM.node_id = '' OR ITEM.node_id is null )\n";
        }
        else
        {
            $nodeIDPart = "       AND ITEM.node_id = '" . eZNetUtils::nodeID() . "'\n";
        }

        $sql = "SELECT ITEM.*\n" .
               "FROM   ezx_ezpnet_mod_patch_item ITEM,\n" .
               "       ezx_ezpnet_module_patch PATCH\n" .
               "WHERE  ITEM.module_patch_id = PATCH.id\n" .
               $nodeIDPart .
               "       AND ITEM.finnished > 0\n" .
               "       AND ITEM.status = " . eZNetPatchItemBase::StatusInstalled . "\n" .
               "       AND ITEM.module_patch_id IN ( " . $modulePatchIDListStr . " )\n" .
               "ORDER BY ITEM.finnished DESC";

        $db = eZDB::instance();
        $resultSet = $db->arrayQuery( $sql, array( 'offset' => 0, 'limit' => 1 ) );

        if ( $resultSet )
        {
            if ( $asObject )
            {
                return new eZNetModulePatchItem( $resultSet[0] );
            }

            return $resultSet[0];
        }

        return null;
    }

    /*!
     \static

     \param installation ID

     Get total issue count
    */
    static function countByInstallationID( $installationID = false,
                                           $status = array( array( eZNetPatchItemBase::StatusNone,
                                                                   eZNetPatchItemBase::StatusNotApproved,
                                                                   eZNetPatchItemBase::StatusPending,
                                                                   eZNetPatchItemBase::StatusInstalling,
                                                                   eZNetPatchItemBase::StatusInstalled,
                                                                   eZNetPatchItemBase::StatusFailed ) ),
                                           $nodeID = false,
                                           $moduleBranchID = false )
    {
        // Define default value
        $status = $status === true ? array( array( eZNetPatchItemBase::StatusNone,
                                                   eZNetPatchItemBase::StatusNotApproved,
                                                   eZNetPatchItemBase::StatusPending,
                                                   eZNetPatchItemBase::StatusInstalling,
                                                   eZNetPatchItemBase::StatusInstalled,
                                                   eZNetPatchItemBase::StatusFailed ) ) : $status;
        $condArray = array( 'status' => $status );
        if ( $installationID )
        {
            $condArray['installation_id'] = $installationID;
        }

        if ( $moduleBranchID )
        {
            $modulePatchIDList = array();
            $modulePatchList = eZNetModulePatch::fetchListByBranchID( $moduleBranchID );
            if ( !$modulePatchList )
                return 0;

            foreach ( $modulePatchList as $modulePatch )
            {
                $modulePatchIDList[] = $modulePatch->attribute( 'id' );
            }
            $condArray['module_patch_id'] = array( $modulePatchIDList );
        }

        $customConds = null;
        if ( $nodeID !== false )
        {
            if ( $nodeID == '' )
                $customConds = " AND ( node_id = '' OR node_id is null )";
            else
                $condArray['node_id'] = $nodeID;
        }

        $resultSet = eZPersistentObject::fetchObjectList( eZNetModulePatchItem::definition(),
                                                          array(),
                                                          $condArray,
                                                          null,
                                                          null,
                                                          false,
                                                          false,
                                                          array( array( 'operation' => 'count(id)',
                                                                        'name' => 'count' ) ),
                                                          null,
                                                          $customConds );
        return $resultSet[0]['count'];
    }

    /*!
     \static

     Fetch list by installation ID
    */
    static function fetchListByInstallationID( $installationID = false,
                                               $nodeID = false,
                                               $moduleBranchID = false,
                                               $offset = 0,
                                               $limit = 10,
                                               $status = array( array( eZNetPatchItemBase::StatusNone,
                                                                       eZNetPatchItemBase::StatusNotApproved,
                                                                       eZNetPatchItemBase::StatusPending,
                                                                       eZNetPatchItemBase::StatusInstalling,
                                                                       eZNetPatchItemBase::StatusInstalled,
                                                                       eZNetPatchItemBase::StatusFailed ) ),
                                               $asObject = true )
    {
        $condArray = array( 'status' => $status );
        if ( $installationID )
        {
            $condArray['installation_id'] = $installationID;
        }

        if ( $moduleBranchID )
        {
            $modulePatchIDList = array();
            $modulePatchList = eZNetModulePatch::fetchListByBranchID( $moduleBranchID );
            if ( !$modulePatchList )
                return array();

            foreach ( $modulePatchList as $modulePatch )
            {
                $modulePatchIDList[] = $modulePatch->attribute( 'id' );
            }
            $condArray['module_patch_id'] = array( $modulePatchIDList );
        }

        $customConds = null;
        if ( $nodeID !== false )
        {
            if ( $nodeID == '' )
                $customConds = " AND ( node_id = '' OR node_id is null )";
            else
                $condArray['node_id'] = $nodeID;
        }

        return eZPersistentObject::fetchObjectList( eZNetModulePatchItem::definition(),
                                                    null,
                                                    $condArray,
                                                    null,
                                                    array( 'limit' => $limit,
                                                           'offset' => $offset ),
                                                    $asObject,
                                                    false,
                                                    null,
                                                    null,
                                                    $customConds );
    }

    /*!
     \static

     Fetch list by installation ID
    */
    static function fetchListByCustomerID( $customerID = false,
                                           $nodeID = false,
                                           $offset = 0,
                                           $limit = 10,
                                           $status = array( array( eZNetPatchItemBase::StatusNone,
                                                                   eZNetPatchItemBase::StatusNotApproved,
                                                                   eZNetPatchItemBase::StatusPending,
                                                                   eZNetPatchItemBase::StatusInstalling,
                                                                   eZNetPatchItemBase::StatusInstalled,
                                                                   eZNetPatchItemBase::StatusFailed ) ),
                                           $asObject = true )
    {
        return eZNetModulePatchItem::fetchListByInstallationID( array( eZNetInstallation::fetchIDListByCustomerID( $customerID ) ),
                                                                $nodeID,
                                                                false,
                                                                $offset,
                                                                $limit,
                                                                $status,
                                                                $asObject );
    }

    /*!
     \static

     \param customer ID

     Get total issue count
    */
    static function countByCustomerID( $customerID = false,
                                       $status = array( array( eZNetPatchItemBase::StatusNone,
                                                               eZNetPatchItemBase::StatusNotApproved,
                                                               eZNetPatchItemBase::StatusPending,
                                                               eZNetPatchItemBase::StatusInstalling,
                                                               eZNetPatchItemBase::StatusInstalled,
                                                               eZNetPatchItemBase::StatusFailed ) ),
                                $nodeID = false )
    {
        return eZNetModulePatchItem::countByInstallationID( array( eZNetInstallation::fetchIDListByCustomerID( $customerID ) ),
                                                            $status,
                                                            $nodeID );
    }

    /*!
     \static
     Fetch by from release tag.

     \param release tag
     \param module branch ID
     \param installation ID
     \param $asObject
    */
    static function fetchByFromReleaseTag( $releaseTag,
                                           $moduleBranchID,
                                           $installationID,
                                           $asObject = true )
    {
        $offset = 0;
        $limit = 10;

        while( $patchItemList = eZNetModulePatchItem::fetchListByInstallationID( $installationID,
                                                                                 eZNetUtils::nodeID(),
                                                                                 $moduleBranchID,
                                                                                 $offset,
                                                                                 $limit,
                                                                                 eZNetPatchItemBase::StatusPending ) )
        {
            $offset += $limit;
            foreach( $patchItemList as $patchItem )
            {
                $patch = $patchItem->attribute( 'module_patch' );
                if ( $patch->option( 'from_release_tag' ) == $releaseTag )
                {
                    return $patchItem;
                }
            }
        }

        return null;
    }

    /*!
     \static

     Create new PatchItem object

    */
    static function create( $patchID,
                            $installationID,
                            $nodeID = false )
    {
        if ( $nodeID === false )
        {
            $nodeID = '';
        }

        // Get patch installation mode
        $status = eZNetPatchItemBase::StatusNone;
        $installation = eZNetInstallation::fetch( $installationID );
        if ( $installation )
        {
            switch( $installation->attribute( 'patch_mode' ) )
            {
                case eZNetInstallation::ModeAutomatic:
                {
                    $status = eZNetPatchItemBase::StatusPending;
                } break;

                default:
                case eZNetInstallation::ModeManual:
                case eZNetInstallation::ModeSemi:
                {
                    $status = eZNetPatchItemBase::StatusNone;
                } break;
            }
        }

        // Create new patch item
        $patchItem = new eZNetModulePatchItem( array( 'module_patch_id' => $patchID,
                                                      'installation_id' => $installationID,
                                                      'node_id' => $nodeID,
                                                      'status' => $status,
                                                      'modified' => time() ) );
        $patchItem->store();
        return $patchItem;
    }

    /*!
     \reimp
    */
    function attribute( $attr, $noFunction = false )
    {
        $retVal = null;
        switch( $attr )
        {
            default:
            {
                $retVal = eZNetPatchItemBase::attribute( eZNetUtils::updateFieldName( $attr ) );
            } break;
        }

        return $retVal;
    }

    /*!
     \reimp
    */
    function setAttribute( $attr, $val )
    {
        switch( $attr )
        {
            default:
            {
                parent::setAttribute( eZNetUtils::updateFieldName( $attr ), $val );
            } break;
        }
    }

    /*!
     Get patch storage dir.

     \return patch storage dir.
    */
    function patchDirectory()
    {
        return eZDir::path( array( eZNetUtils::storageDirectory() ,
                                   'patch_module' ,
                                   md5( '-' . eZNetUtils::nodeID() . '-' ),
                                   $this->attribute( 'module_patch_id' ) ,
                                   substr( eZNetUtils::nodeID(), 0, 8 ) ) );
    }

    /*!
     Check if required patch is properly installed

     \return true if required patch is installed, false if not.
    */
    function requiredPatchInstalled()
    {
        $patch = $this->attribute( 'patch' );

        switch( $patch->attribute( 'required_patch_id' ) )
        {
            case eZNetPatchBase::RequiredNone:
            {
                if ( $patch->isBaseRelease() )
                {
                    return true;
                }
            } break;

            default:
            {
                $moduleBranch = $patch->attribute( 'module_branch' );
                if ( $patch->option( 'from_release_tag' ) == $moduleBranch->attribute( 'version_value' ) )
                {
                    return true;
                }

                $requiredPatchItem = $this->attribute( 'required_patch_item' );
                if ( !$requiredPatchItem )
                {
                    return false;
                }
                if ( $requiredPatchItem->attribute( 'status' ) == eZNetPatchItemBase::StatusInstalled )
                {
                    return true;
                }
            } break;
        }

        return false;
    }

    /*!
     Uninstall patch currently beeing installed.
    */
    function uninstall()
    {
    }

    /*!
     Get patch storage path. Used for storing files related to patch.
    */
    function storagePath()
    {
        return eZDir::path( array( eZNetUtils::storagePath(),
                                   'module_patch',
                                   md5( '-' . eZNetUtils::nodeID() . '-' ),
                                   $this->attribute( 'module_patch_id' ) ? $this->attribute( 'module_patch_id' ) : '_tmp' ) );
    }

    /*!
     \static
     List of statuses users are allowed to change from ez.no
    */
    static function changeAllowList()
    {
        return array( eZNetPatchItemBase::StatusNone,
                      eZNetPatchItemBase::StatusNotApproved );
    }

    /*!
     \static
     List of statuses users are allowed to change to.
    */
    static function changeToAllowList()
    {
        return array( eZNetPatchItemBase::StatusNone,
                      eZNetPatchItemBase::StatusPending,
                      eZNetPatchItemBase::StatusNotApproved );
    }

    /*!
     \static
     Status name map

     return status name map
    */
    static function activeStatusNameMap()
    {
        return array( eZNetPatchItemBase::StatusNone => ezi18n( 'ez_network', 'None' ),
                      eZNetPatchItemBase::StatusNotApproved => ezi18n( 'ez_network', 'Do not install' ),
                      eZNetPatchItemBase::StatusPending => ezi18n( 'ez_network', 'Install' ),
                      eZNetPatchItemBase::StatusInstalling => ezi18n( 'ez_network', 'Installing' ),
                      eZNetPatchItemBase::StatusInstalled => ezi18n( 'ez_network', 'Installed' ),
                      eZNetPatchItemBase::StatusFailed => ezi18n( 'ez_network', 'Failed' ) );
    }

    /*!
     \static
     Status name map

     return status name map
    */
    static function passiveStatusNameMap()
    {
        return array( eZNetPatchItemBase::StatusNone => ezi18n( 'ez_network', 'None' ),
                      eZNetPatchItemBase::StatusNotApproved => ezi18n( 'ez_network', 'Discarded by user' ),
                      eZNetPatchItemBase::StatusPending => ezi18n( 'ez_network', 'Pending' ),
                      eZNetPatchItemBase::StatusInstalling => ezi18n( 'ez_network', 'Installing' ),
                      eZNetPatchItemBase::StatusInstalled => ezi18n( 'ez_network', 'Installed' ),
                      eZNetPatchItemBase::StatusFailed => ezi18n( 'ez_network', 'Failed' ) );
    }

    /*!
     \static
     Get next patch item to install

     \param module branch id
     \param $asObject = true

     \return module patch item
    */
    static function fetchNextPatchItem( $moduleBranchID,
                                        $asObject = true )
    {
        $nextItem = false;
        $latestInstalled = eZNetModulePatchItem::fetchLatestInstalled( $moduleBranchID, $asObject );

        if ( !$latestInstalled )
        {
            if ( $currentInstallation = eZNetInstallation::fetchCurrent() )
            {
                $moduleBranch = eZNetModuleBranch::fetch( $moduleBranchID );
                $nextItem = eZNetModulePatchItem::fetchByFromReleaseTag( $moduleBranch->attribute( 'version_value' ),
                                                                         $moduleBranchID,
                                                                         $currentInstallation->attribute( 'id' ) );
            }
        }
        else
        {
            $nextItem = $latestInstalled->nextPatchItem();
        }

        return $nextItem;
    }

    /*!
     \reimp
    */
    static function patchIDFieldName()
    {
        return 'module_patch_id';
    }

    /*!
     \reimp
    */
    static function patchClassName()
    {
        return 'eZNetModulePatch';
    }

    /*!
     \reimp
     \static

     Update all modified timestamps.

     \param \a $diffTS ( optional ).
    */
    static function updateModifiedAll( $diffTS = 0 )
    {
        parent::updateModifiedAllByClass( $diffTS, get_class() );
    }

    /*!
     \reimp
     \static

     Fetch eZNetPatchItemBase by patch item ID

     \param patch item ID
     \param $asObject

     \return eZNetPatchItem
    */
    static function fetch( $id, $asObject = true )
    {
        return parent::fetchByClass( $id, $asObject, get_class() );
    }

    /*!
     \reimp
     \static

     Fetch eZNetPatchItem by patch and installation ID
    */
    static function fetchByPatchID( $patchID,
                                    $installationID,
                                    $nodeID = false,
                                    $asObject = true )
    {
        return parent::fetchByPatchIDAndClass( $patchID, $installationID, $nodeID, $asObject, get_class() );
    }
}
?>
