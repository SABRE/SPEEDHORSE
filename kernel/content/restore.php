<?php
//
// Created on: <03-May-2002 15:17:01 bf>
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




$objectID = $Params['ObjectID'];
$module = $Params['Module'];

$object = eZContentObject::fetch( $objectID );
if ( !is_object( $object ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
if ( $object->attribute( 'status' ) != eZContentObject::STATUS_ARCHIVED )
{
    eZDebug::writeError( "Object with ID " . (int)$objectID . " is not archived, cannot restore." );
    return $module->redirectToView( 'trash' );
}

$ini = eZINI::instance();
$userClassID = $ini->variable( "UserSettings", "UserClassID" );

if ( $module->isCurrentAction( 'Cancel' ) )
{
    return $module->redirectToView( 'trash' );
}

$class = $object->contentClass();
$version = $object->attribute( 'current' );

$location = null;
$assignments = $version->attribute( 'node_assignments' );
foreach ( $assignments as $assignment )
{
    $opCode = $assignment->attribute( 'op_code' );
    $opCode &= ~1;
    // We only include assignments which create or nops.
    if ( $opCode == eZNodeAssignment::OP_CODE_CREATE_NOP ||
         $opCode == eZNodeAssignment::OP_CODE_NOP )
    {
        $node = $assignment->attribute( 'parent_node_obj' );
        if ( !$node )
        {
            continue;
        }
        if ( $assignment->attribute( 'is_main' ) )
        {
            $parentNode = $assignment->attribute( 'parent_node_obj' );
            $parentNodeObject = $parentNode->attribute( 'object' );
            $canCreate = $parentNode->checkAccess( 'create', $class->attribute( 'id' ), $parentNodeObject->attribute( 'contentclass_id' ) ) == 1;
            if ( !$canCreate )
            {
                continue;
            }
            $location = $assignment;
            break;
        }
        else if ( !$location )
        {
            $parentNode = $assignment->attribute( 'parent_node_obj' );
            $parentNodeObject = $parentNode->attribute( 'object' );
            $canCreate = $parentNode->checkAccess( 'create', $class->attribute( 'id' ), $parentNodeObject->attribute( 'contentclass_id' ) ) == 1;
            if ( !$canCreate )
            {
                continue;
            }
            $location = $assignment;
        }
    }
}

if ( $module->isCurrentAction( 'Confirm' ) )
{
    $type = $module->actionParameter( 'RestoreType' );
    if ( $type == 1 )
    {
        $selectedNodeIDArray = array( $location->attribute( 'parent_node' ) );
        $module->setCurrentAction( 'AddLocation' );
    }
    elseif ( $type == 2 )
    {
        $languageCode = $object->attribute( 'initial_language_code' );
        eZContentBrowse::browse( array( 'action_name' => 'AddNodeAssignment',
                                        'description_template' => 'design:content/browse_placement.tpl',
                                        'keys' => array( 'class' => $class->attribute( 'id' ),
                                                         'class_id' => $class->attribute( 'identifier' ),
                                                         'classgroup' => $class->attribute( 'ingroup_id_list' ),
                                                         'section' => $object->attribute( 'section_id' ) ),
                                        'ignore_nodes_select' => array(),
                                        'ignore_nodes_click'  => array(),
                                        'persistent_data' => array( 'ContentObjectID' => $objectID,
                                                                    'AddLocationAction' => '1' ),
                                        'content' => array( 'object_id' => $objectID,
                                                            'object_version' => $version->attribute( 'version' ),
                                                            'object_language' => $languageCode ),
                                        'cancel_page' => '/content/trash/',
                                        'from_page' => "/content/restore/" . $objectID ),
                                 $module );

        return;
    }
}

if ( $module->isCurrentAction( 'AddLocation' ) )
{
    // If $selectedNodeIDArray is already set then use it as it is,
    // if not get the browse data.
    if ( !isset( $selectedNodeIDArray ) )
    {
        $selectedNodeIDArray = eZContentBrowse::result( 'AddNodeAssignment' );
        if ( !$selectedNodeIDArray )
        {
            return $module->redirectToView( 'trash' );
        }
    }

    $db = eZDB::instance();
    $db->begin();
    $locationAdded = false;
    $mainNodeID = false;

    $newLocationList    = array();
    $failedLocationList = array();
    foreach ( $selectedNodeIDArray as $selectedNodeID )
    {
        $parentNode = eZContentObjectTreeNode::fetch( $selectedNodeID );
        $parentNodeObject = $parentNode->attribute( 'object' );

        $canCreate = $parentNode->checkAccess( 'create', $class->attribute( 'id' ), $parentNodeObject->attribute( 'contentclass_id' ) ) == 1;

        if ( $canCreate )
        {
            if ( $mainNodeID === false )
            {
                $isMain = true;
            }
            $newLocationList[] = array( 'parent_node_id' => $selectedNodeID,
                                        'is_main'        => $isMain );

            $locationAdded = true;
        }
        else
        {
            $failedLocationList[] = array( 'parent_node_id' => $selectedNodeID );
        }
    }

    // Check if we have failures
    if ( count( $failedLocationList ) > 0 )
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }

    // Remove all existing assignments, only our new ones should be present.
    foreach ( $version->attribute( 'node_assignments' ) as $assignment )
    {
        $assignment->purge();
    }

    // Add all new locations
    foreach ( $newLocationList as $newLocation )
    {
        $version->assignToNode( $newLocation['parent_node_id'], $newLocation['is_main'] );
    }

    $object->setAttribute( 'status', eZContentObject::STATUS_DRAFT );
    $object->store();
    $version->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
    $version->store();

    $user = eZUser::currentUser();
    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $objectID,
                                                                                 'version' => $version->attribute( 'version' ) ) );
    if ( ( array_key_exists( 'status', $operationResult ) && $operationResult['status'] != eZModuleOperationInfo::STATUS_CONTINUE ) )
    {
        switch( $operationResult['status'] )
        {
            case eZModuleOperationInfo::STATUS_HALTED:
            case eZModuleOperationInfo::STATUS_CANCELLED:
            {
                $module->redirectToView( 'trash' );
            }
        }
    }
    $objectID = $object->attribute( 'id' );
    $object = eZContentObject::fetch( $objectID );
    $mainNodeID = $object->attribute( 'main_node_id' );

    eZContentObjectTrashNode::purgeForObject( $objectID  );

    if ( $locationAdded )
    {
        if ( $object->attribute( 'contentclass_id' ) == $userClassID )
        {
            eZUser::purgeUserCacheByUserId( $object->attribute( 'id' ) );
        }
    }

    eZContentObject::fixReverseRelations( $objectID, 'restore' );

    $db->commit();
    $module->redirectToView( 'view', array( 'full', $mainNodeID ) );
    return;
}

$tpl = eZTemplate::factory();

$res = eZTemplateDesignResource::instance();

$designKeys = array( array( 'object', $object->attribute( 'id' ) ), // Object ID
                     array( 'remote_id', $object->attribute( 'remote_id' ) ),
                     array( 'class', $class->attribute( 'id' ) ), // Class ID
                     array( 'class_identifier', $class->attribute( 'identifier' ) ) ); // Class identifier

$res->setKeys( $designKeys );

$Result = array();

$tpl->setVariable( "object",   $object );
$tpl->setVariable( "version",  $version );
$tpl->setVariable( "location", $location );

$Result['content'] = $tpl->fetch( 'design:content/restore.tpl' );
$Result['path'] = array( array( 'uri'  => false,
                                'text' => ezpI18n::tr( "kernel/content/restore", "Restore object" ) ),
                         array( 'uri'  => false,
                                'text' => $object->attribute( 'name' ) ) );

?>
