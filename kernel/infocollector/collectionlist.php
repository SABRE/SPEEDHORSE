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
$objectID = $Params['ObjectID'];
$offset = $Params['Offset'];

if( !is_numeric( $offset ) )
{
    $offset = 0;
}


if( $module->isCurrentAction( 'RemoveCollections' ) && $http->hasPostVariable( 'CollectionIDArray' ) )
{
    $collectionIDArray = $http->postVariable( 'CollectionIDArray' );
    $http->setSessionVariable( 'CollectionIDArray', $collectionIDArray );
    $http->setSessionVariable( 'ObjectID', $objectID );

    $collections = count( $collectionIDArray );

    $tpl = eZTemplate::factory();
    $tpl->setVariable( 'module', $module );
    $tpl->setVariable( 'collections', $collections );
    $tpl->setVariable( 'object_id', $objectID );
    $tpl->setVariable( 'remove_type', 'collections' );

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:infocollector/confirmremoval.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezpI18n::tr( 'kernel/infocollector', 'Collected information' ) ) );
    return;
}


if( $module->isCurrentAction( 'ConfirmRemoval' ) )
{
    $collectionIDArray = $http->sessionVariable( 'CollectionIDArray' );

    if( is_array( $collectionIDArray ) )
    {
        foreach( $collectionIDArray as $collectionID )
        {
            eZInformationCollection::removeCollection( $collectionID );
        }
    }

    $objectID = $http->sessionVariable( 'ObjectID' );
    $module->redirectTo( '/infocollector/collectionlist/' . $objectID );
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

$object = false;

if( is_numeric( $objectID ) )
{
    $object = eZContentObject::fetch( $objectID );
}

if( !$object )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$collections = eZInformationCollection::fetchCollectionsList( $objectID, /* object id */
                                                              false, /* creator id */
                                                              false, /* user identifier */
                                                              array( 'limit' => $limit,'offset' => $offset ) /* limit array */ );
$numberOfCollections = eZInformationCollection::fetchCollectionsCount( $objectID );

$viewParameters = array( 'offset' => $offset );
$objectName = $object->attribute( 'name' );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'module', $module );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'collection_array', $collections );
$tpl->setVariable( 'collection_count', $numberOfCollections );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:infocollector/collectionlist.tpl' );
$Result['path'] = array( array( 'url' => '/infocollector/overview',
                                'text' => ezpI18n::tr( 'kernel/infocollector', 'Collected information' ) ),
                         array( 'url' => false,
                                'text' => $objectName ) );

?>
