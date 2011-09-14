<?php
//
// Definition of eZNetClientInfo class
//
// Created on: <21-Sep-2006 14:12:49 hovik>
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

/*! \file eznetclientinfo.php
*/

/*!
  \class eZNetClientInfo eznetclientinfo.php
  \brief The class eZNetClientInfo does

  - Checks if DB Update is needed.
    - Creates cronjob lock if DB update is on progress. ( uses ezsite_data table for this operation )

  - Stores version information about the network client.
*/

class eZNetClientInfo
{
    /*!
     Constructor
    */
    function eZNetClientInfo()
    {
        $this->DB = eZDB::instance();
    }

    /*!
     \static

     Get instance of the eZNetClientInfo class.
    */
    static function instance()
    {
        return new eZNetClientInfo();
    }

    /*!
     Check if version is validated. This will check the installed Network version with the
     one indicated in the database. In case there is a missmatch, it'll check the version path
     for required DB updates, and perform them.

     While performing the updates, this function will act as a cronjob lock. If locked, the
     function will return false.

     If the lock has been present for more than 4 hours, the lock will be removed.

     \return true if everything is ok.
             false if locked.
    */
    function validate()
    {
        if ( $this->locked() )
        {
            return false;
        }

        if ( !$this->correctVersion() )
        {
            if ( !$this->lock() )
            {
                eZNetUtils::log( 'ERROR : Could not create lock.' );
                return false;
            }

            eZNetUtils::log( 'Incorrect version. ' . $this->currentVersion() . ' should be: ' . max( array_keys( $this->VersionArray ) ) );

            $this->DB->begin();
            $this->upgrade();
            $this->updateVersion();
            $this->unLock();
            $this->DB->commit();

            eZNetUtils::log( 'Updated to version: ' . max( array_keys( $this->VersionArray ) ) );
        }

        return true;
    }

    /*!
     Update Network version
    */
    function updateVersion()
    {
        if ( $this->currentVersion() )
        {
            $sql = 'UPDATE ezsite_data
                    SET value=\'' . $this->DB->escapeString( max( array_keys( $this->VersionArray ) ) ) . '\'
                    WHERE name=\'' . $this->DB->escapeString( eZNetClientInfo::VERSION_NAME ) . '\'';
        }
        else
        {
            $sql = 'INSERT INTO ezsite_data ( name, value )
                    VALUES ( \'' . $this->DB->escapeString( eZNetClientInfo::VERSION_NAME ) . '\',
                             \'' . $this->DB->escapeString( max( array_keys( $this->VersionArray ) ) ) . '\' )';
        }

        $this->DB->query( $sql );
    }

    /*!
     \public

     Checks if current execution is the first run after an update.

     \return true if DB has been cleared recently.
    */
    function isFirstRun()
    {
        $sql = 'SELECT count(*) count FROM ezsite_data
                WHERE name=\'' . $this->DB->escapeString( eZNetClientInfo::EZNET_DB ) . '\'';

        $result = $this->DB->arrayQuery( $sql );

        return $result[0]['count'] > 0;
    }

    /*!
     \private

     Stores a value to DB it means DB has been cleared

    */
    function setEmptyDB()
    {
        // If empty DB value already exists
        if ( $this->isFirstRun() )
        {
            return;
        }

        $this->DB->begin();
        $sql = 'INSERT INTO ezsite_data ( name, value )
                VALUES ( \'' . $this->DB->escapeString( eZNetClientInfo::EZNET_DB ) . '\',
                         \'' . $this->DB->escapeString( eZNetClientInfo::EMPTY_DB ) . '\')';

        $this->DB->query( $sql );
        $this->DB->commit();
    }

    /*!
     \public

     Drops notice from DB. It means that the execution is not already the first.

    */
    function dropFirstRun()
    {
        $this->DB->begin();
        $sql = 'DELETE FROM ezsite_data
                WHERE name=\'' . $this->DB->escapeString( eZNetClientInfo::EZNET_DB ) . '\'';

        $this->DB->query( $sql );
        $this->DB->commit();
    }

    /*!
     Run functions required to upgrade to latest version.
     */
    function upgrade()
    {
        foreach( $this->upgradeFunctionList() as $function )
        {
            call_user_func( array( $this, $function ) );
        }
    }

    /*!
     Generate list of functions required to run to upgrade version.

     \return array of functions to run
    */
    function upgradeFunctionList()
    {
        $functionList = array();
        $currentVersion = $this->currentVersion();

        foreach( $this->VersionArray as $version => $upgradeDefinition )
        {
            if ( $version <= $currentVersion )
            {
                continue;
            }
            if ( isset( $upgradeDefinition['functionList'] ) )
            {
                $functionList = array_merge( $functionList, $upgradeDefinition['functionList'] );
            }
        }

        return $functionList;
    }

    /*!
     Check that the correct version is installed.

     \return true if the version is correct, false if not.
    */
    function correctVersion()
    {
        $currentVersion = $this->currentVersion();

        if ( !$currentVersion )
        {
            return false;
        }

        $correctVersion = max( array_keys( $this->VersionArray ) );
        return ( $currentVersion == $correctVersion );
    }

    /*!
     Get currect version number

     \return current version, false if not set.
    */
    function currentVersion()
    {
        $sql = 'SELECT value FROM ezsite_data
                WHERE name=\'' . $this->DB->escapeString( eZNetClientInfo::VERSION_NAME ) . '\'';
        $resultSet = $this->DB->arrayQuery( $sql );

        if ( empty( $resultSet ) )
        {
            return false;
        }

        return $resultSet[0]['value'];
    }

    /*!
     Check if installation is locked. If the installation is locked, check if the lock is oder than
     the specified lock time.

     \retrun true if locked, false if not locked.
     */
    function locked()
    {
        $sql = 'SELECT value FROM ezsite_data
                WHERE name=\'' . $this->DB->escapeString( eZNetClientInfo::LOCK_NAME ) . '\'';
        $resultSet = $this->DB->arrayQuery( $sql );

        if ( empty( $resultSet ) )
        {
            return false;
        }

        if ( $resultSet[0]['value'] + eZNetClientInfo::LOCK_TIMEOUT < time() )
        {
            return $this->unLock();
        }

        return true;
    }

    /*!
     Remove lock.

     \return true if successfull.
     */
    function unLock()
    {
        $this->DB->begin();
        $sql = 'DELETE FROM ezsite_data WHERE name=\'' . $this->DB->escapeString( eZNetClientInfo::LOCK_NAME ) . '\'';
        $this->DB->query( $sql );
        return $this->DB->commit();
    }

    /*!
     Create lock.

     \return true if successfull.
    */
    function lock()
    {
        $this->DB->begin();
        $sql = 'INSERT INTO ezsite_data ( name, value )
                VALUES ( \'' . $this->DB->escapeString( eZNetClientInfo::LOCK_NAME ) . '\', \'' . time() . '\' )';
        $this->DB->query( $sql );
        return $this->DB->commit();
    }

    /*!
     Update DB Schema
    */
    function updateSchema()
    {
        $schemaDir = eZExtension::baseDirectory() . '/' . eZINI::instance( 'network.ini' )->variable( 'NetworkSettings', 'ExtensionPath' ) . '/share';
        $schemaFile = 'db_schema.dba';
        $dbContents = eZDbSchema::read( $schemaDir . '/' . $schemaFile, true );
        $schema = isset( $dbContents['schema'] ) ? $dbContents['schema'] : false;
        eZNetUtils::createTable( $schema );
    }

    /*!
     Clear DB
    */
    function clearDB()
    {
        $clearDBArray = array( 'ezx_ezpnet_branch',
                               'ezx_ezpnet_installation',
                               'ezx_ezpnet_mon_group',
                               'ezx_ezpnet_mon_item',
                               'ezx_ezpnet_mon_result',
                               'ezx_ezpnet_mon_value',
                               'ezx_ezpnet_soap_log',
                               'ezx_ezpnet_storage',
                               'ezx_ezpnet_patch',
                               'ezx_ezpnet_large_store',
                               'ezx_ezpnet_patch_item',
                               'ezx_ezpnet_module_branch',
                               'ezx_ezpnet_patch_sql_st',
                               'ezx_ezpnet_module_inst',
                               'ezx_ezpnet_module_patch',
                               'ezx_ezpnet_mod_patch_item',
                               //'ezx_oauth_client_consumer_user'  Will currently lead to server rejecting to setup new
                                                                // auth key since it already exists on server.
                               );

        $this->DB->begin();
        foreach( $clearDBArray as $dbName )
        {
            $sql = 'DELETE FROM ' . $dbName;
            $this->DB->query( $sql );
        }

        // We notice that it will be the first run after clearing of DB.
        $this->setEmptyDB();

        $this->DB->commit();
    }

    var $DB = null;
    var $VersionArray = array( '0.1' => array( 'functionList' => array( 'updateSchema', 'clearDB' ) ),
                               '1.0.1' => array( 'functionList' => array( 'clearDB' ) ),
                               '1.1.0' => array( 'functionList' => array( 'updateSchema', 'clearDB' ) ),
                               '1.1.1' => array( 'functionList' => array( 'updateSchema', 'clearDB' ) ),
                               '1.1.2' => array( 'functionList' => array( 'updateSchema', 'clearDB' ) ),
                               '1.2.0' => array( 'functionList' => array( 'updateSchema', 'clearDB' ) ),
                               '1.2.1' => array( 'functionList' => array( 'updateSchema', 'clearDB' ) ),
                               );

    /// Public constants
    const LOCK_TIMEOUT = 14400;
    const VERSION_NAME = 'ezxnet_version';
    const LOCK_NAME = 'ezxnet_lock';
    const EZNET_DB = 'ezxnet_db';
    const EMPTY_DB = 'empty';
}

?>
