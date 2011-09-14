<?php
//
// Created on: <15-Aug-2002 16:40:11 sp>
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

$contentObjectID = $Params['ContentObjectID'];
$contentObjectAttributeID = $Params['ContentObjectAttributeID'];
$contentObject = eZContentObject::fetch( $contentObjectID );
if ( !is_object( $contentObject ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
$currentVersion = $contentObject->attribute( 'current_version' );

if ( isset(  $Params['Version'] ) && is_numeric( $Params['Version'] ) )
     $version = $Params['Version'];
else
     $version = $currentVersion;

$contentObjectAttribute = eZContentObjectAttribute::fetch( $contentObjectAttributeID, $version, true );
if ( !is_object( $contentObjectAttribute ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}
$contentObjectIDAttr = $contentObjectAttribute->attribute( 'contentobject_id' );
if ( $contentObjectID != $contentObjectIDAttr or !$contentObject->attribute( 'can_read' ) )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

// Get locations.
$nodeAssignments = $contentObject->attribute( 'assigned_nodes' );
if ( count( $nodeAssignments ) === 0 )
{
    // oops, no locations. probably it's related object. Let's check his owners
    $ownerList = eZContentObject::fetch( $contentObjectID )->reverseRelatedObjectList( false, false, false, false );
    foreach ( $ownerList as $owner )
    {
        if ( is_object( $owner ) )
        {
            $ownerNodeAssignments = $owner->attribute( 'assigned_nodes' );
            $nodeAssignments = array_merge( $nodeAssignments, $ownerNodeAssignments );
        }
    }
}

// If exists location that current user has access to and location is visible.
$canAccess = false;
foreach ( $nodeAssignments as $nodeAssignment )
{
    if ( ( eZContentObjectTreeNode::showInvisibleNodes() || !$nodeAssignment->attribute( 'is_invisible' ) ) and $nodeAssignment->canRead() )
    {
        $canAccess = true;
        break;
    }
}
if ( !$canAccess )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

// If $version is not current version (published)
// we should check permission versionRead for the $version.
if ( $version != $currentVersion )
{
    $versionObj = eZContentObjectVersion::fetchVersion( $version, $contentObjectID );
    if ( is_object( $versionObj ) and !$versionObj->canVersionRead() )
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$fileHandler = eZBinaryFileHandler::instance();
$result = $fileHandler->handleDownload( $contentObject, $contentObjectAttribute, eZBinaryFileHandler::TYPE_FILE );

if ( $result == eZBinaryFileHandler::RESULT_UNAVAILABLE )
{
    eZDebug::writeError( "The specified file could not be found." );
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

?>
