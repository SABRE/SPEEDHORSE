<?php
//
// Created on: <13-Feb-2005 03:13:00 bh>
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


$http = eZHTTPTool::instance();
$module = $Params['Module'];
$offset = $Params['Offset'];

if( !is_numeric( $offset ) )
{
    $offset = 0;
}


if( $module->isCurrentAction( 'RemoveObjectCollection' ) && $http->hasPostVariable( 'ObjectIDArray' ) )
{
    $objectIDArray = $http->postVariable( 'ObjectIDArray' );
    $http->setSessionVariable( 'ObjectIDArray', $objectIDArray );

    $collections = 0;

    foreach( $objectIDArray as $objectID )
    {
        $collections += eZInformationCollection::fetchCollectionCountForObject( $objectID );
    }

    $tpl = eZTemplate::factory();
    $tpl->setVariable( 'module', $module );
    $tpl->setVariable( 'collections', $collections );
    $tpl->setVariable( 'remove_type', 'objects' );

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:infocollector/confirmremoval.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezpI18n::tr( 'kernel/infocollector', 'Collected information' ) ) );
    return;
}


if( $module->isCurrentAction( 'ConfirmRemoval' ) )
{

    $objectIDArray = $http->sessionVariable( 'ObjectIDArray' );

    if( is_array( $objectIDArray) )
    {
        foreach( $objectIDArray as $objectID )
        {
            eZInformationCollection::removeContentObject( $objectID );
        }
    }
}


if( eZPreferences::value( 'admin_infocollector_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_infocollector_list_limit' ) )
    {
        case '2': { $limit = 25; } break;
        case '3': { $limit = 50; } break;
        default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}


$db = eZDB::instance();
$objects = $db->arrayQuery( 'SELECT DISTINCT ezcontentobject.id AS contentobject_id,
                                             ezcontentobject.name,
                                             ezcontentobject_tree.main_node_id,
                                             ezcontentclass.serialized_name_list,
                                             ezcontentclass.identifier AS class_identifier
                             FROM ezcontentobject,
                                  ezcontentobject_tree,
                                  ezcontentclass
                             WHERE ezcontentobject_tree.contentobject_id = ezcontentobject.id
                                   AND ezcontentobject.contentclass_id = ezcontentclass.id
                                   AND ezcontentclass.version = ' . eZContentClass::VERSION_STATUS_DEFINED . '
                                   AND ezcontentobject.id IN
                                   ( SELECT DISTINCT ezinfocollection.contentobject_id FROM ezinfocollection )
                             ORDER BY ezcontentobject.name ASC',
                             array( 'limit'  => (int)$limit,
                                    'offset' => (int)$offset ) );

$infoCollectorObjectsQuery = $db->arrayQuery( 'SELECT COUNT( DISTINCT ezinfocollection.contentobject_id ) as count
                                               FROM ezinfocollection,
                                                    ezcontentobject,
                                                    ezcontentobject_tree
                                               WHERE
                                                    ezinfocollection.contentobject_id=ezcontentobject.id
                                                    AND ezinfocollection.contentobject_id=ezcontentobject_tree.contentobject_id' );
$numberOfInfoCollectorObjects = 0;

if ( $infoCollectorObjectsQuery )
{
    $numberOfInfoCollectorObjects = $infoCollectorObjectsQuery[0]['count'];
}

foreach ( array_keys( $objects ) as $i )
{
    $firstCollections = eZInformationCollection::fetchCollectionsList( (int)$objects[$i]['contentobject_id'], /* object id */
                                                                       false, /* creator id */
                                                                       false, /* user identifier */
                                                                       array( 'limit' => 1, 'offset' => 0 ), /* limitArray */
                                                                       array( 'created', true ), /* sortArray */
                                                                       false  /* asObject */
                                                                     );
    $objects[$i]['first_collection'] = $firstCollections[0]['created'];

    $lastCollections = eZInformationCollection::fetchCollectionsList( (int)$objects[$i]['contentobject_id'], /* object id */
                                                                      false, /* creator id */
                                                                      false, /* user identifier */
                                                                      array( 'limit' => 1, 'offset' => 0 ), /* limitArray */
                                                                      array( 'created', false ), /* sortArray */
                                                                      false  /* asObject */
                                                                    );
    $objects[$i]['last_collection'] = $lastCollections[0]['created'];

    $objects[$i]['class_name'] = eZContentClassNameList::nameFromSerializedString( $objects[$i]['serialized_name_list'] );
    $objects[$i]['collections']= eZInformationCollection::fetchCollectionCountForObject( $objects[$i]['contentobject_id'] );
}

$viewParameters = array( 'offset' => $offset );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'module', $module );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'object_array', $objects );
$tpl->setVariable( 'object_count', $numberOfInfoCollectorObjects );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:infocollector/overview.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/infocollector', 'Collected information' ) ) );

?>
