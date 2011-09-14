<?php
//
//
// Created on: <19-Oct-2004 18:03:49 vs>
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



$module = $Params['Module'];
$http   = eZHTTPTool::instance();

if ( $http->hasSessionVariable( 'AssignmentRemoveData' ) )
{
    $data = $http->sessionVariable( 'AssignmentRemoveData' );
    $removeList   = $data['remove_list'];
    $objectID     = $data['object_id'];
    $editVersion  = $data['edit_version'];
    $editLanguage = $data['edit_language'];
    $fromLanguage = $data['from_language'];

    $object = eZContentObject::fetch( $objectID );
    if ( !$object )
        return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    if ( !$object->checkAccess( 'edit' ) )
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    unset( $object );
}
else
{
    eZDebug::writeError( "No assignments passed to content/removeassignment" );
    return $module->redirectToView( 'view', array( 'full', 2 ) );
}

// process current action
if ( $module->isCurrentAction( 'ConfirmRemoval' ) )
{
    $http->removeSessionVariable( 'AssignmentRemoveData' );

    $assignments     = eZNodeAssignment::fetchListByID( $removeList );
    $mainNodeChanged = false;

    $db = eZDB::instance();
    $db->begin();
    foreach ( $assignments as $assignment )
    {
        $assignmentID = $assignment->attribute( 'id' );
        if ( $assignment->attribute( 'is_main' ) )
            $mainNodeChanged = true;
        eZNodeAssignment::purgeByID( $assignmentID );
    }
    if ( $mainNodeChanged )
        eZNodeAssignment::setNewMainAssignment( $objectID, $editVersion );

    $db->commit();

    return $module->redirectToView( 'edit', array( $objectID, $editVersion, $editLanguage, $fromLanguage ) );
}
else if ( $module->isCurrentAction( 'CancelRemoval' ) )
{
    $http->removeSessionVariable( 'AssignmentRemoveData' );

    return $module->redirectToView( 'edit', array( $objectID, $editVersion, $editLanguage, $fromLanguage ) );
}

// default action: show the confirmation dialog
$assignmentsToRemove = eZNodeAssignment::fetchListByID( $removeList );
$removeList = array();
$canRemoveAll = true;
foreach ( $assignmentsToRemove as $assignment )
{
    $node = $assignment->attribute( 'node' );

    // skip assignments which don't have associated node or node with no children
    if ( !$node )
        continue;
    $count = $node->subTreeCount( array( 'Limitation' => array() ) );
    if ( $count < 1 )
        continue;

    // Find the number of items in the subtree we are allowed to remove
    // if this differs from the total count it means we have items we cannot remove
    // We do this by fetching the limitation list for content/remove
    // and passing it to the subtre count function.
    $currentUser = eZUser::currentUser();
    $accessResult = $currentUser->hasAccessTo( 'content', 'remove' );
    $canRemoveSubtree = true;
    if ( $accessResult['accessWord'] == 'limited' )
    {
        $limitationList = $accessResult['policies'];
        $removeableChildCount = $node->subTreeCount( array( 'Limitation' => $limitationList ) );
        $canRemoveSubtree = ( $removeableChildCount == $count );
    }
    if ( !$canRemoveSubtree )
        $canRemoveAll = false;
    $object = $node->object();
    $class = $object->contentClass();

    $removeList[] = array( 'node' => $node,
                           'object' => $object,
                           'class' => $class,
                           'count' => $count,
                           'can_remove' => $canRemoveSubtree,
                           'child_count' => $count );
}
unset( $assignmentsToRemove );

$assignmentData = array( 'object_id'      => $objectID,
                         'object_version' => $editVersion,
                         'remove_list'    => $removeList );
$info = array( 'can_remove_all' => $canRemoveAll );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'assignment_data', $assignmentData );
$tpl->setVariable( 'remove_info', $info );

$Result = array();
$Result['content'] = $tpl->fetch( "design:content/removeassignment.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/content', 'Remove location' ) ) );
?>
