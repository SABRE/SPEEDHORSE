<?php
//
// Created on: <25-Sep-2003 12:47:21 bf>
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
$viewMode = 'full';
$nodeID = $Params['NodeID'];
$userParameters = array();

if ( isset( $Params['UserParameters'] ) )
{
    $userParameters = $Params['UserParameters'];
}

if ( !$nodeID )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
$node = eZContentObjectTreeNode::fetch( $nodeID );
if ( !$node )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( $node->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes() )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}

$object = $node->attribute( 'object' );
if ( !$object )
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
if ( !$object->attribute( 'can_read' ) )
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

$http = eZHTTPTool::instance();

$tpl = eZTemplate::factory();

$icMap = array();
if ( $http->hasSessionVariable( 'InformationCollectionMap' ) )
    $icMap = $http->sessionVariable( 'InformationCollectionMap' );
$icID = false;
if ( isset( $icMap[$object->attribute( 'id' )] ) )
    $icID = $icMap[$object->attribute( 'id' )];

$informationCollectionTemplate = eZInformationCollection::templateForObject( $object );
$attributeHideList = eZInformationCollection::attributeHideList();

$tpl->setVariable( 'node_id', $nodeID );
$tpl->setVariable( 'collection_id', $icID );
$tpl->setVariable( 'node', $node );
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'viewmode', $viewMode );
$tpl->setVariable( 'view_parameters', $userParameters );
$tpl->setVariable( 'attribute_hide_list', $attributeHideList );
$tpl->setVariable( 'error', false );

$section = eZSection::fetch( $object->attribute( 'section_id' ) );
if ( $section )
    $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );

$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                      array( 'node', $node->attribute( 'node_id' ) ),
                      array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                      array( 'class', $object->attribute( 'contentclass_id' ) ),
                      array( 'class_identifier', $node->attribute( 'class_identifier' ) ),
                      array( 'viewmode', $viewMode ),
                      array( 'remote_id', $object->attribute( 'remote_id' ) ),
                      array( 'node_remote_id', $node->attribute( 'remote_id' ) ),
                      array( 'navigation_part_identifier', $navigationPartIdentifier ),
                      array( 'depth', $node->attribute( 'depth' ) ),
                      array( 'url_alias', $node->attribute( 'url_alias' ) ),
                      array( 'class_group', $object->attribute( 'match_ingroup_id_list' ) ),
                      array( 'state', $object->attribute( 'state_id_array' ) ),
                      array( 'state_identifier', $object->attribute( 'state_identifier_array' ) )
                      ) );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/collectedinfo/' . $informationCollectionTemplate . '.tpl' );
$Result['section_id'] = $object->attribute( 'section_id' );
$Result['node_id'] = $node->attribute( 'node_id' );
$Result['view_parameters'] = $userParameters;
$Result['navigation_part'] = $navigationPartIdentifier;

$title = $object->attribute( 'name' );
if ( $tpl->hasVariable( 'title' ) )
    $title = $tpl->variable( 'title' );

// create path
$parents = $node->attribute( 'path' );

$path = array();
foreach ( $parents as $parent )
{
    $path[] = array( 'text' => $parent->attribute( 'name' ),
                     'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                     'url_alias' => $parent->attribute( 'url_alias' ),
                     'node_id' => $parent->attribute( 'node_id' ) );
}

$titlePath = $path;
$path[] = array( 'text' => $object->attribute( 'name' ),
                 'url' => false,
                 'url_alias' => false,
                 'node_id' => $node->attribute( 'node_id' ) );

$titlePath[] = array( 'text' => $title,
                      'url' => false,
                      'url_alias' => false );

$Result['path'] = $path;
$Result['title_path'] = $titlePath;

?>
