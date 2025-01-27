<?php
/**
 * File containing the ezpContentLocation class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package API
 */

/**
 * This class is used to manipulate a specific content location.
 * It currently acts among others as a wrapper around eZContentObjectTreeNode, and therefore exposes
 * some of
 *
 * @package API
 *
 * @property node_id eZContentObjectTreeNode
 * @property node_id eZContentObjectTreeNode
 */
class ezpContentLocation extends ezpLocation
{
    /**
     * Returns the ezpContentLocation object for a particular node by ID
     * @param int $nodeId
     * @return ezpContentLocation
     * @throws ezcBaseException When the node is not found
     */
    public static function fetchByNodeId( $nodeId )
    {
        $node = eZContentObjectTreeNode::fetch( $nodeId );
        if ( $node instanceof eZContentObjectTreeNode )
        {
            return self::fromNode( $node );
        }
        else
        {
            // @TODO Currently this exception is only in rest package. Needs to be fixed.
            throw new ezpContentNotFoundException( "Unable to find node with ID $nodeId" );
        }
    }

    /**
     * Returns the ezpContentLocation object for a particular node object
     * @param eZContentObjectTreeNode $node
     * @return ezpContentLocation
     */
    public static function fromNode( eZContentObjectTreeNode $node )
    {
        $location = new ezpContentLocation();
        $location->node = $node;

        return $location;
    }

    /**
     * Wrapper for node attributes
     */
    public function __get( $property )
    {
        if ( in_array( $property, self::$validNodeAttributes ) && $this->node->hasAttribute( $property ) )
            return $this->node->attribute( $property );
        else
            throw new ezcBasePropertyNotFoundException( $property );
    }

    protected $node;

    protected static $validNodeAttributes = array( 'node_id', 'path_string', 'name', 'url_alias', 'is_main', 'remote_id' );
}
?>
