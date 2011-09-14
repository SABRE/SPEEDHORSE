<?php
//
// Created on: <12-���-2002 16:14:13 sp>
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



$http = eZHTTPTool::instance();

$tpl = eZTemplate::factory();

$Module = $Params['Module'];
$ObjectID = $Params['ObjectID'];

$NodeID = $Params['NodeID'];
if ( !isset( $EditVersion ) )
    $EditVersion = $Params['EditVersion'];

$object = eZContentObject::fetch( $ObjectID );
if ( $object === null )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$object->attribute( 'can_remove' ) )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

$version = $object->version( $EditVersion );
$node = eZContentObjectTreeNode::fetchNode( $ObjectID, $NodeID );
if ( $node !== null )
    $ChildObjectsCount = $node->subTreeCount();
else
    $ChildObjectsCount = 0;
$ChildObjectsCount .= " ";
if ( $ChildObjectsCount == 1 )
    $ChildObjectsCount .= ezpI18n::tr( 'kernel/content/removenode',
                                  'child',
                                  '1 child' );
else
    $ChildObjectsCount .= ezpI18n::tr( 'kernel/content/removenode',
                                  'children',
                                  'several children' );

if ( $Module->isCurrentAction( 'ConfirmAssignmentRemove' ) )
{
    $nodeID = $http->postVariable( 'RemoveNodeID' ) ;
    $version->removeAssignment( $nodeID );
    $Module->redirectToView( "edit", array( $ObjectID, $EditVersion ) );
}
elseif ( $Module->isCurrentAction( 'CancelAssignmentRemove' ) )
{
    $Module->redirectToView( "edit", array( $ObjectID, $EditVersion ) );
}

$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'edit_version', $EditVersion );
$tpl->setVariable( 'content_version', $version );
$tpl->setVariable( 'ChildObjectsCount', $ChildObjectsCount );
$tpl->setVariable( 'node', $node );


$Result['content'] = $tpl->fetch( 'design:node/removenode.tpl' );

$Result['path'] = array( array( 'text' => $object->attribute( 'name' ),
                                'url' => false ) );

?>
