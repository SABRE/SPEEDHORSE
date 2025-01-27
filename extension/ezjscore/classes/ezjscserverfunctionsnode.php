<?php
//
// Definition of ezjscServerFunctionsNode class
//
// Created on: <01-Jun-2010 00:00:00 ls>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
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

/**
 * ezjscServerFunctionsNode class definition that provide node fetch functions
 *
 */
class ezjscServerFunctionsNode extends ezjscServerFunctions
{
    /**
     * Returns a subtree node items for given parent node
     *
     * Following parameters are supported:
     * ezjscnode::subtree::parent_node_id::limit::offset::sort::order
     *
     * @since 1.2
     * @param mixed $args
     * @return array
     */
    public static function subTree( $args )
    {
        $parentNodeID = isset( $args[0] ) ? $args[0] : null;
        $limit = isset( $args[1] ) ? $args[1] : 25;
        $offset = isset( $args[2] ) ? $args[2] : 0;
        $sort = isset( $args[3] ) ? self::sortMap( $args[3] ) : 'published';
        $order = isset( $args[4] ) ? $args[4] : false;

        if ( !$parentNodeID )
        {
            throw new ezcBaseFunctionalityNotSupportedException( 'Fetch node list', 'Parent node id is not valid' );
        }

        $node = eZContentObjectTreeNode::fetch( $parentNodeID );
        if ( !$node instanceOf eZContentObjectTreeNode )
        {
            throw new ezcBaseFunctionalityNotSupportedException( 'Fetch node list', "Parent node '$parentNodeID' is not valid" );
        }

        $params = array( 'Depth' => 1,
                         'Limit' => $limit,
                         'Offset' => $offset,
                         'SortBy' => array( array( $sort, $order ) ),
                         'DepthOperator' => 'eq',
                         'AsObject' => true );

       // fetch nodes and total node count
        $count = $node->subTreeCount( $params );
        if ( $count )
        {
            $nodeArray = $node->subTree( $params );
        }
        else
        {
            $nodeArray = false;
        }

        // generate json response from node list
        if ( $nodeArray )
        {
            $list = ezjscAjaxContent::nodeEncode( $nodeArray, array( 'formatDate' => 'shortdatetime',
                                                                     'fetchThumbPreview' => true,
                                                                     'fetchSection' => true,
                                                                     'fetchCreator' => true,
                                                                     'fetchClassIcon' => true ), 'raw' );
        }
        else
        {
            $list = array();
        }

        return array( 'parent_node_id' => $parentNodeID,
                      'count' => count( $nodeArray ),
                      'total_count' => (int)$count,
                      'list' => $list,
                      'limit' => $limit,
                      'offset' => $offset,
                      'sort' => $sort,
                      'order' => $order );
    }

    /**
     * Returns a node data for given object / node id
     *
     * Following parameters are supported:
     * ezjscnode::load::embed_id[::attribute[::load_image_size]]
     *
     * eg: ezjscnode::load::ezobject_46::image::large
     * eg: ezjscnode::load::eznode_44::summary
      *eg: ezjscnode::load::44::summary (44 is in this case node id)
     *
     * @since 1.2
     * @param mixed $args
     * @throws InvalidArgumentException
     * @return array
     */
    public static function load( $args )
    {
        $embedObject = false;
        if ( isset( $args[0] ) && $args[0] )
        {
            $embedType = 'eznode';
            if (  is_numeric( $args[0] ) )
                $embedId = $args[0];
            else
                list($embedType, $embedId) = explode('_', $args[0]);

            if ( $embedType === 'eznode' || strcasecmp( $embedType  , 'eznode'  ) === 0 )
                $embedObject = eZContentObject::fetchByNodeID( $embedId );
            else
                $embedObject = eZContentObject::fetch( $embedId );
        }

        if ( !$embedObject instanceof eZContentObject )
        {
           throw new InvalidArgumentException( "Argument 1: '$embedType\_$embedId' does not map to a valid content object" );
        }

        // Params for node to json encoder
        $params    = array('loadImages' => true);
        $params['imagePreGenerateSizes'] = array('small', 'original');

        // look for attribute parameter ( what attribute we should load )
        if ( isset( $args[1] ) && $args[1] )
            $params['dataMap'] = array( $args[1] );

        // what image sizes we want returned with full data ( url++ )
        if ( isset( $args[2] ) && $args[2] )
            $params['imagePreGenerateSizes'][] = $args[2];

        // Simplify and load data in accordance to $params
        return ezjscAjaxContent::simplify( $embedObject, $params );
    }

    /**
     * Updating priority sorting for given node
     *
     * @since 1.2
     * @param mixed $args
     * @return array
     */
    public static function updatePriority( $args )
    {
        $http = eZHTTPTool::instance();

        if ( !$http->hasPostVariable('ContentNodeID')
                || !$http->hasPostVariable('PriorityID')
                    || !$http->hasPostVariable('Priority') )
        {
            return array();
        }

        $contentNodeID = $http->postVariable('ContentNodeID');
        $priorityArray = $http->postVariable('Priority');
        $priorityIDArray = $http->postVariable('PriorityID');

        if ( eZOperationHandler::operationIsAvailable( 'content_updatepriority' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content', 'updatepriority',
                                                             array( 'node_id' => $contentNodeID,
                                                                    'priority' => $priorityArray,
                                                                    'priority_id' => $priorityIDArray ), null, true );
        }
        else
        {
            eZContentOperationCollection::updatePriority( $contentNodeID, $priorityArray, $priorityIDArray );
        }

        if ( $http->hasPostVariable( 'ContentObjectID' ) )
        {
            $objectID = $http->postVariable( 'ContentObjectID' );
            eZContentCacheManager::clearContentCache( $objectID );
        }
    }

    /**
     * A helper function which maps sort keys from encoded JSON node
     * to supported values
     *
     * @since 1.2
     * @param string $sort
     * @return string
     */
    protected static function sortMap( $sort )
    {
        switch ( $sort )
        {
            case 'modified_date':
                $sortKey = 'modified';
                break;
            case 'published_date':
                $sortKey = 'published';
                break;
            default:
                $sortKey = $sort;
        }

        return $sortKey;
    }
}

?>
