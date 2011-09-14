<?php
//
// Created on: <16-���-2003 16:09:52 sp>
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



// Check for extension
require_once( 'kernel/common/ezincludefunctions.php' );
eZExtension::activateExtensions();
// Extension check end

$ini = eZINI::instance( 'content.ini' );
$unpublishClasses = $ini->variable( 'UnpublishSettings','ClassList' );

$rootNodeIDList = $ini->variable( 'UnpublishSettings','RootNodeList' );

$currentDate = time();

foreach( $rootNodeIDList as $nodeID )
{
    $rootNode = eZContentObjectTreeNode::fetch( $nodeID );

    $articleNodeArray = $rootNode->subTree( array( 'ClassFilterType' => 'include',
                                                    'ClassFilterArray' => $unpublishClasses ) );

    foreach ( $articleNodeArray as $articleNode )
    {
        $article = $articleNode->attribute( 'object' );
        $dataMap = $article->attribute( 'data_map' );

        $dateAttribute = $dataMap['unpublish_date'];

        if ( $dateAttribute === null )
            continue;

        $date = $dateAttribute->content();
        $articleRetractDate = $date->attribute( 'timestamp' );
        if ( $articleRetractDate > 0 && $articleRetractDate < $currentDate )
        {
            // Clean up content cache
            eZContentCacheManager::clearContentCacheIfNeeded( $article->attribute( 'id' ) );

            $article->removeThis( $articleNode->attribute( 'node_id' ) );
        }
    }
}


?>
