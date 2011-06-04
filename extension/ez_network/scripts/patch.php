<?php
//
// Created on: <10-Oct-2006 10:55:51 hovik>
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

/*! \file patch.php
*/

/*!
  \class Patch patch.php
  \brief The class Patch does

*/

require 'autoload.php';
require 'extension/ez_network/classes/include_all.php';

@ini_set( 'memory_limit', '500M' );

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli = eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for initialize script" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => "Executes eZ Publish Network patch script.",
                                      'debug-message' => '',
                                      'use-session' => true,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );
$script->startup();

$options = $script->getOptions();
$script->initialize();

$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;

if ( $siteAccess )
{
    changeSiteAccessSetting( $siteaccess, $siteAccess );
}


if ( !$script->isInitialized() )
{
    $cli->error( 'Error initializing script: ' . $script->initializationError() . '.' );
    $script->shutdown();
    exit();
}

// Script start
if ( !function_exists( 'readline' ) )
{
    function readline( $prompt = '' )
        {
            echo $prompt . ' ';
            return trim( fgets( STDIN ) );
        }
}

if ( !function_exists( 'getUserInput' ) )
{
    function getUserInput( $query, $acceptValues )
    {
        $validInput = false;
        while( !$validInput )
        {
            $input = readline( $query );
            if ( $acceptValues === false ||
                 in_array( $input, $acceptValues ) )
            {
                $validInput = true;
            }
        }
        return $input;
    }
}

$cli->setUseStyles( true );

// If patch file supplied, do not fetch installation, etc.
// Start patch installation immidiatly
if ( isset( $options['arguments'] ) &&
     isset( $options['arguments'][0] ) &&
     file_exists( $options['arguments'][0] ) )
{
    $db = eZDB::instance();
    $db->begin();

    $filename = $options['arguments'][0];
    $patchItem = new eZNetPatchItem( array() );

    $domDocument = new DOMDocument( '1.0', 'utf-8' );
    $success = $domDocument->load( $filename );
    if ( !$success )
    {
        $cli->output( 'Loading patch failed.' );
        $db->rollback();
        $script->shutdown();
        return;
    }

    // Get and set original patch ID
    $patchRoot = $domDocument->documentElement;
    $patchItem->setOriginalPatchID( $patchRoot->getAttribute( 'id' ) );

    // Install patch
    $cli->output( 'Starting patch installation.' );
    $result = $patchItem->installPatch( $domDocument->documentElement, $cli );
    if ( !$result )
    {
        $cli->output( 'Patching failed.' );
        $db->rollback();
    }
    else
    {
        $cli->output( 'Finished running patch installation.' );
        $db->commit();
    }

}
else
{
    $installation = eZNetInstallation::fetchCurrent();

    if ( !$installation )
    {
        $cli->output( 'Installation not found. This probably means that your ' .
                      'eZ Network set up has not syncrinized with the ez.no servers.' . "\n" .
                      'Please run the sync_network cronjob part, and try again.' );
        return $script->shutdown();
    }

    // Create new patch crontroller object.
    $patchController = new eZPNetPatchCrontroller( $installation,
                                                   $cli,
                                                   $script );

    // Check if a patch is beeing installed.
    if ( $patchController->processInstallingPatches() === NULL )
    {
        return $script->shutdown();
    }

    // Select moduleBranch
    $moduleBranch = $patchController->selectMainBranch();
    if ( $moduleBranch === NULL )
    {
        return $script->shutdown();
    }

    // Select which patch from the module branch to install.
    $patchItem = $patchController->selectPatchItem( $moduleBranch );
    if ( $patchItem === NULL )
    {
        return $script->shutdown();
    }

    // Install selected patch item
    if ( $patchController->installPatchItem( $patchItem ) === NULL )
    {
        return $script->shutdown();
    }

    // Sync soap classes.
    $classSyncOrder = eZNetSOAPSyncAdvanced::orderClassListByDependencies( array( 'eZNetPatchItem',
                                                                                  'eZNetModulePatchItem' ) );

    $syncINI = eZINI::instance( 'sync.ini' );
    $Server = $syncINI->variable( 'NetworkSettings', 'Server' );
    $Port = $syncINI->variable( 'NetworkSettings', 'Port' );
    $Path = $syncINI->variable( 'NetworkSettings', 'Path' );

    // If use of SSL fails the client must attempt to use HTTP
    $Port = eZNetSOAPSync::getPort( $Server, $Path, $Port );

    $client = new eZSOAPClient( $Server, $Path, $Port );

    foreach ( $classSyncOrder as $className )
    {
        $messageSync = new eZNetSOAPSyncClient( call_user_func( array( $className, 'definition' ) ) );
        $result = $messageSync->syncronizePushClient( $client );
        if ( !$result )
        {
            $cli->output( 'Syncronization of: ' . $className . ' failed. See error log for more information' );
        }
        else
        {
            $cli->output( 'Exported : ' . $result['export_count'] . ' elements to Class : ' . $result['class_name'] );
        }
    }

}

$script->shutdown();



class eZPNetPatchCrontroller
{

    /*!
     \constructor
    */
    function eZPNetPatchCrontroller( $installation,
                                     $cli,
                                     $script )
    {
        $this->installation = $installation;
        $this->cli =& $cli;
        $this->script =& $script;
    }

    /*!
     \static
     Get user input

     \param Question
     \param list of accepted input values ( false if everything is acceptable )
    */
    function getUserInput( $query, $acceptValues )
    {
        $validInput = false;
        while( !$validInput )
        {
            $input = readline( $query );
            if ( $acceptValues === false ||
                 in_array( $input, $acceptValues ) )
            {
                $validInput = true;
            }
        }
        return $input;
    }

    /*!
     Get main option map

     \return option map array ( 1, => eZ<idx> => $patchItem )
    */
    function mainOptionMap()
    {
        $optionMap = array();
        $count = 0;
        foreach( $this->installation->attribute( 'module_branch_list' ) as $moduleBranch )
        {
            ++$count;
            $optionMap[$count] = $moduleBranch;
        }

        return $optionMap;
    }

    /*!
     \static
     Install specified patch item

     \param patchItem
    */
    function installPatchItem( $patchItem )
    {
        if ( !$patchItem )
        {
            $this->cli->output( $this->cli->stylize( 'error', 'ERROR 10: ' ) . 'incorrect option. Could not find patch.' );
        }

        $patchDomDocument = $patchItem->loadPatchFile();
        if ( !$patchDomDocument )
        {
            $this->cli->output( $this->cli->stylize( 'error', 'ERROR 11: ' ) . 'could not load patch data.' );
            return NULL;
        }

        $rootNode = $patchDomDocument->documentElement;
        if ( !$rootNode )
        {
            $this->cli->output( $this->cli->stylize( 'error', 'ERROR 12: ' ) . 'invalid patch data.' );
            return NULL;
        }

        $patchItem->begin();
        // Install patch
        if ( !$patchItem->installPatch( $rootNode, $this->cli ) )
        {
            $patch = $patchItem->attribute( 'patch' );
            $this->cli->output( 'Failed installing patch: ' . $patch->attribute( 'name' ) );
            $patchItem->abort();
        }
        else
        {
            $patchItem->updateSQLStatus( $this->cli );
            $this->cli->output( 'Patch installed successfully.' );
            $patchItem->commit();
        }

        return true;
    }

    /*!
     \static
     Select patch item.

     \param moduleBranch

     \return eZNetPatchItemBase
    */
    function selectPatchItem( $moduleBranch )
    {
        $offset = 0;
        $limit = 10;
        $optionMap = array();
        $statusNameMap = eZNetPatchItemBase::passiveStatusNameMap();
        $count = 0;

        switch( is_object( $moduleBranch ) )
        {
            // eZ Publish branch
            case false:
            {
                // List available patches ( and recommended to install )
                $this->cli->output( 'Select which patch you would like to install:' );

                $suggestedPatchItem = eZNetPatchItem::fetchNextPatchItem();
                while( $patchItemList = eZNetPatchItem::fetchListByInstallationID( $this->installation->attribute( 'id' ),
                                                                                   eZNetUtils::nodeID(),
                                                                                   $offset,
                                                                                   $limit,
                                                                                   array( array( eZNetPatchItemBase::StatusPending,
                                                                                                 eZNetPatchItemBase::StatusInstalled,
                                                                                                 eZNetPatchItemBase::StatusFailed ) ) ) )
                {
                    $offset += $limit;
                    foreach( $patchItemList as $patchItem )
                    {
                        $patch = $patchItem->attribute( 'patch' );
                        if ( $patch )
                        {
                            ++$count;
                            $optionMap[$count] = $patchItem;
                            $this->cli->output( '[' . $this->cli->stylize( 'green', $count ) . '] - ' .
                                                $patch->attribute( 'name' ) .
                                                ' ( ' . $statusNameMap[$patchItem->attribute( 'status' )] . ' )' .
                                                ( $suggestedPatchItem &&
                                                  $patchItem->attribute( 'id' ) == $suggestedPatchItem->attribute( 'id' ) ? ' - Suggested' : '' ) );
                        }
                    }
                }
            } break;

            // module branch
            default:
            {
                $this->cli->output( 'Select which module patch you wish to install ( ' . $moduleBranch->attribute( 'name' ) .' ):' );

                $suggestedPatchItem = eZNetModulePatchItem::fetchNextPatchItem( $moduleBranch->attribute( 'id' ) );
                while( $patchItemList = eZNetModulePatchItem::fetchListByInstallationID( $this->installation->attribute( 'id' ),
                                                                                         eZNetUtils::nodeID(),
                                                                                         $moduleBranch->attribute( 'id' ),
                                                                                         $offset,
                                                                                         $limit,
                                                                                         array( array( eZNetPatchItemBase::StatusPending,
                                                                                                       eZNetPatchItemBase::StatusInstalled,
                                                                                                       eZNetPatchItemBase::StatusFailed ) ) ) )
                {
                    $offset += $limit;
                    foreach( $patchItemList as $patchItem )
                    {
                        $patch = $patchItem->attribute( 'patch' );
                        if ( $patch )
                        {
                            ++$count;
                            $optionMap[$count] = $patchItem;
                            $this->cli->output( '[' . $this->cli->stylize( 'green', $count ) . '] - ' .
                                                $patch->attribute( 'name' ) .
                                                ' ( ' . $statusNameMap[$patchItem->attribute( 'status' )] . ' )' .
                                                ( $suggestedPatchItem &&
                                                  $patchItem->attribute( 'id' ) == $suggestedPatchItem->attribute( 'id' ) ? ' - Suggested' : '' ) );
                        }
                    }
                }
            } break;
        }

        $input = eZPNetPatchCrontroller::getUserInput( 'Input option [1..' . $count . '] or \'q\' to quit: ',
                                                       array_merge( array( 'q' ), array_keys( $optionMap ) ) );

        if ( $input == 'q' )
        {
            return NULL;
        }


        // return eZNetPatchItemBase
        return $optionMap[$input];

    }

    /*!
     \static
     Select which module/eZ Publish installation to install.

     \return module branch to install. true if eZ Publish was selected.
    */
    function selectMainBranch()
    {
        // Select to install eZ Publish patch or module patch
        $this->cli->output( 'Select which patch group you wish to install patches from:' );

        $optionMap = array();
        $count = 1;
        $this->cli->output( '[' . $this->cli->stylize( 'green', $count ) . '] - eZ Publish' );
        $optionMap[$count] = true;
        foreach( $this->installation->attribute( 'module_branch_list' ) as $moduleBranch )
        {
            ++$count;
            $optionMap[$count] = $moduleBranch;
            $this->cli->output( '[' . $this->cli->stylize( 'green', $count ) . '] - ' . $moduleBranch->attribute( 'name' ) );
        }
        $input = eZPNetPatchCrontroller::getUserInput( 'Input option [1..' . $count . '] or \'q\' to quit: ',
                                                       array_merge( array( 'q' ), array_keys( $optionMap ) ) );
        if ( $input == 'q' )
        {
            return NULL;
        }

        return $optionMap[$input];
    }

    /*!
     \static
     \protected
     Process patches with status "installing".

     \return Will call
    */
    function processInstallingPatches()
    {
        while( $patchItemInstallingList = eZNetPatchItem::fetchListByInstallationID( $this->installation->attribute( 'id' ),
                                                                                     eZNetUtils::nodeID(),
                                                                                     0,
                                                                                     100,
                                                                                     eZNetPatchItemBase::StatusInstalling ) )
        {
            $count = 0;
            $optionMap = array();
            $this->cli->output( $this->cli->stylize( 'warning', 'Warning: ' ) . 'There are patches currently beeing installed:' );
            foreach( $patchItemInstallingList as $patchItem )
            {
                ++$count;
                if ( $optionMap[$count] = $patchItem )
                {
                    $patch = $patchItem->attribute( 'patch' );
                    $this->cli->output( '[' . $this->cli->stylize( 'green', $count ) . '] - Installing: ' . $patch->attribute( 'name' ) );
                }
            }
            $input = eZPNetPatchCrontroller::getUserInput( 'Input option [1..' . $count . '] to set patch status to pending or \'q\' to quit: ',
                                                           array_merge( array( 'q' ), array_keys( $optionMap ) ) );

            if ( $input == 'q' )
            {
                return NULL;
            }
            $patchItem = $optionMap[$input];
            $patchItem->setAttribute( 'status', eZNetPatchItemBase::StatusPending );
            $patchItem->setAttribute( 'modified', time() );
            $patchItem->sync();
        }

        return true;
    }

    /// Class variables
    var $installation;
    var $cli;
    var $script;
}

?>
