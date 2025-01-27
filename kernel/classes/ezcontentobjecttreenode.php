<?php
//
// Definition of eZContentObjectTreeNode class
//
// Created on: <10-Jul-2002 19:28:22 sp>
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

/*!
  \class eZContentObjectTreeNode ezcontentobjecttreenode.php
  \brief The class eZContentObjectTreeNode does

\verbatim

Some algorithms
----------
1. Adding new Node
Enter  1 - parent_node
       2 - contentobject_id,  ( that is like a node value )

(a) - get path_string, depth for parent node to built path_string  and to count depth for new one
(c) - calculating attributes for new node and inserting it
Returns node_id for added node


2. Deleting node ( or subtree )
Enter - node_id

3. Move subtree in tree
Enter node_id,new_parent_id


4. fetching subtree

\endverbatim

*/

class eZContentObjectTreeNode extends eZPersistentObject
{
    const SORT_FIELD_PATH = 1;
    const SORT_FIELD_PUBLISHED = 2;
    const SORT_FIELD_MODIFIED = 3;
    const SORT_FIELD_SECTION = 4;
    const SORT_FIELD_DEPTH = 5;
    const SORT_FIELD_CLASS_IDENTIFIER = 6;
    const SORT_FIELD_CLASS_NAME = 7;
    const SORT_FIELD_PRIORITY = 8;
    const SORT_FIELD_NAME = 9;
    const SORT_FIELD_MODIFIED_SUBNODE = 10;
    const SORT_FIELD_NODE_ID = 11;
    const SORT_FIELD_CONTENTOBJECT_ID = 12;

    const SORT_ORDER_DESC = 0;
    const SORT_ORDER_ASC = 1;

    /*!
     Constructor
    */
    function eZContentObjectTreeNode( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        static $definition = array( "fields" => array( "node_id" => array( 'name' => "NodeID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "parent_node_id" => array( 'name' => "ParentNodeID",
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true,
                                                                    'foreign_class' => 'eZContentObjectTreeNode',
                                                                    'foreign_attribute' => 'node_id',
                                                                    'multiplicity' => '1..*' ),
                                         "main_node_id" => array( 'name' => "MainNodeID",
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true,
                                                                  'foreign_class' => 'eZContentObjectTreeNode',
                                                                  'foreign_attribute' => 'node_id',
                                                                  'multiplicity' => '1..*' ),
                                         "contentobject_id" => array( 'name' => "ContentObjectID",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZContentObject',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '1..*' ),
                                         'contentobject_version' => array( 'name' => 'ContentObjectVersion',
                                                                           'datatype' => 'integer',
                                                                           'default' => 0,
                                                                           'required' => true ),
                                         'contentobject_is_published' => array( 'name' => 'ContentObjectIsPublished',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true ),
                                         "depth" => array( 'name' => "Depth",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'sort_field' => array( 'name' => 'SortField',
                                                                'datatype' => 'integer',
                                                                'default' => self::SORT_FIELD_PATH,
                                                                'required' => true ),
                                         'sort_order' => array( 'name' => 'SortOrder',
                                                                'datatype' => 'integer',
                                                                'default' => self::SORT_ORDER_ASC,
                                                                'required' => true ),
                                         'priority' => array( 'name' => 'Priority',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'modified_subnode' => array( 'name' => 'ModifiedSubNode',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "path_string" => array( 'name' => "PathString",
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "path_identification_string" => array( 'name' => "PathIdentificationString",
                                                                                'datatype' => 'text',
                                                                                'default' => '',
                                                                                'required' => true ),
                                         'remote_id' => array( 'name' => 'RemoteID',
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         "is_hidden" => array( 'name' => "IsHidden",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "is_invisible" => array( 'name' => "IsInvisible",
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ) ),
                      "keys" => array( "node_id" ),
                      "function_attributes" => array( "name" => "getName",
                                                      'data_map' => 'dataMap',
                                                      'remote_id' => 'remoteID', // Note: This overrides remote_id field
                                                      "object" => "object",
                                                      "subtree" => "subTree",
                                                      "children" => "children",
                                                      "children_count" => "childrenCount",
                                                      'view_count' => 'viewCount',
                                                      'contentobject_version_object' => 'contentObjectVersionObject',
                                                      'sort_array' => 'sortArray',
                                                      'can_read' => 'canRead',
                                                      'can_pdf' =>  'canPdf',
                                                      'can_create' => 'canCreate',
                                                      'can_edit' => 'canEdit',
                                                      'can_hide' => 'canHide',
                                                      'can_remove' => 'canRemove',
                                                      'can_move' => 'canMoveFrom',
                                                      'can_move_from' => 'canMoveFrom',
                                                      'can_add_location' => 'canAddLocation',
                                                      'can_remove_location' => 'canRemoveLocation',
                                                      'can_view_embed' => 'canViewEmbed',
                                                      'is_main' => 'isMain',
                                                      'creator' => 'creator',
                                                      "path_with_names" => "pathWithNames",
                                                      "path" => "fetchPath",
                                                      'path_array' => 'pathArray',
                                                      "parent" => "fetchParent",
                                                      'url' => 'url',
                                                      'url_alias' => 'urlAlias',
                                                      'class_identifier' => 'classIdentifier',
                                                      'class_name' => 'className',
                                                      'hidden_invisible_string' => 'hiddenInvisibleString',
                                                      'hidden_status_string' => 'hiddenStatusString',
                                                      'classes_js_array' => 'availableClassesJsArray',
                                                      'is_container' => 'classIsContainer' ),
                      "increment_key" => "node_id",
                      "class_name" => "eZContentObjectTreeNode",
                      "name" => "ezcontentobject_tree" );
        return $definition;
    }

    /*!
     Creates a new tree node and returns it.
     \param $parentNodeID The ID of the parent or \c null if the node is not known yet.
     \param $contentObjectID The ID of the object it points to or \c null if it is not known yet.
     \param $contentObjectVersion The version of the object or \c 0 if not known yet.
     \param $sortField Number describing the field to sort by, or \c 0 if not known yet.
     \param $sortOrder Which way to sort, \c true means ascending while \c false is descending.
     \note The attribute \c remote_id will get an automatic and unique value.
    */
    static function create( $parentNodeID = null, $contentObjectID = null, $contentObjectVersion = 0,
                      $sortField = 0, $sortOrder = true )
    {
        $row = array( 'node_id' => null,
                      'main_node_id' => null,
                      'parent_node_id' => $parentNodeID,
                      'contentobject_id' => $contentObjectID,
                      'contentobject_version' => $contentObjectVersion,
                      'contentobject_is_published' => false,
                      'depth' => 1,
                      'path_string' => null,
                      'path_identification_string' => null,
                      'is_hidden' => false,
                      'is_invisible' => false,
                      'sort_field' => $sortField,
                      'sort_order' => $sortOrder,
                      'modified_subnode' => 0,
                      'remote_id' => eZRemoteIdUtility::generate( 'node' ),
                      'priority' => 0 );
        $node = new eZContentObjectTreeNode( $row );
        return $node;
    }

    /*!
     \return a map with all the content object attributes where the keys are the
             attribute identifiers.
     \sa eZContentObject::fetchDataMap
    */
    function dataMap()
    {
        return $this->object()->fetchDataMap( $this->attribute( 'contentobject_version' ) );
    }

    /*!
     Get remote id of content node, the remote ID is often used to synchronise imports and exports.
     If there is no remote ID a new unique one will be generated.
    */
    function remoteID()
    {
        $remoteID = eZPersistentObject::attribute( 'remote_id', true );
        if ( !$remoteID )
        {
            $this->setAttribute( 'remote_id', eZRemoteIdUtility::generate( 'node' ) );
            $this->sync( array( 'remote_id' ) );
            $remoteID = eZPersistentObject::attribute( 'remote_id', true );
        }

        return $remoteID;
    }

    /*!
     \return true if this node is the main node.
     */
    function isMain()
    {
        return $this->NodeID == $this->MainNodeID;
    }

    /*!
     \return the ID of the class attribute with the given ID.
     False is returned if no class/attribute by that identifier is found.
     If multiple classes have the same identifier, the first found is returned.
    */
    static function classAttributeIDByIdentifier( $identifier )
    {
        return eZContentClassAttribute::classAttributeIDByIdentifier( $identifier );
    }

    /*!
     \return the ID of the class with the given ID.
     False is returned if no class by that identifier is found.
     If multiple classes have the same identifier, the first found is returned.
    */
    static function classIDByIdentifier( $identifier )
    {
        return eZContentClass::classIDByIdentifier( $identifier );
    }

    /*!
     \return \c true if the node can be read by the current user.
     \sa checkAccess().
    */
    function canRead( )
    {
        if ( !isset( $this->Permissions["can_read"] ) )
        {
            $this->Permissions["can_read"] = $this->checkAccess( 'read' );
        }
        return ( $this->Permissions["can_read"] == 1 );
    }

    /*!
     \return \c true if the current user can create a pdf of this content object.
    */
    function canPdf( )
    {
        if ( !isset( $this->Permissions["can_pdf"] ) )
        {
            $this->Permissions["can_pdf"] = $this->checkAccess( 'pdf' );
        }
        return ( $this->Permissions["can_pdf"] == 1 );
    }


    /*!
     \return \c true if the node can be viewed as embeded object by the current user.
     \sa checkAccess().
    */
    function canViewEmbed( )
    {
        if ( !isset( $this->Permissions["can_view_embed"] ) )
        {
            $this->Permissions["can_view_embed"] = $this->checkAccess( 'view_embed' );
        }
        return ( $this->Permissions["can_view_embed"] == 1 );
    }

    /*!
     \return \c true if the node can be edited by the current user.
     \sa checkAccess().
    */
    function canEdit( )
    {
        if ( !isset( $this->Permissions["can_edit"] ) )
        {
            $this->Permissions["can_edit"] = $this->checkAccess( 'edit' );
            if ( $this->Permissions["can_edit"] != 1 )
            {
                 $user = eZUser::currentUser();
                 if ( $user->id() == $this->ContentObject->attribute( 'id' ) )
                 {
                     $access = $user->hasAccessTo( 'user', 'selfedit' );
                     if ( $access['accessWord'] == 'yes' )
                     {
                         $this->Permissions["can_edit"] = 1;
                     }
                 }
            }
        }
        return ( $this->Permissions["can_edit"] == 1 );
    }

    /*!
     \return \c true if the node can be hidden by the current user.
     \sa checkAccess().
    */
    function canHide( )
    {
        if ( !isset( $this->Permissions["can_hide"] ) )
        {
            $this->Permissions["can_hide"] = $this->checkAccess( 'hide' );
        }
        return ( $this->Permissions["can_hide"] == 1 );
    }

    /*!
     \return \c true if the current user can create a new node as child of this node.
     \sa checkAccess().
    */
    function canCreate( )
    {
        if ( !isset( $this->Permissions["can_create"] ) )
        {
            $this->Permissions["can_create"] = $this->checkAccess( 'create' );
        }
        return ( $this->Permissions["can_create"] == 1 );
    }

    /*!
     \return \c true if the node can be removed by the current user.
     \sa checkAccess().
    */
    function canRemove( )
    {
        if ( !isset( $this->Permissions["can_remove"] ) )
        {
            $this->Permissions["can_remove"] = $this->checkAccess( 'remove' );
        }
        return ( $this->Permissions["can_remove"] == 1 );
    }

    /*!
     Check if the node can be moved. (actually checks 'edit' and 'remove' permissions)
     \return \c true if the node can be moved by the current user.
     \sa checkAccess().
     \deprecated The function canMove() is preferred since its naming is clearer.
    */
    function canMove()
    {
        return $this->canMoveFrom();
    }

    /*!
     Check if the node can be moved. (actually checks 'edit' and 'remove' permissions)
     \return \c true if the node can be moved by the current user.
     \sa checkAccess().
    */
    function canMoveFrom( )
    {
        if ( !isset( $this->Permissions['can_move_from'] ) )
        {
            $this->Permissions['can_move_from'] = $this->checkAccess( 'edit' ) && $this->checkAccess( 'remove' );
        }
        return ( $this->Permissions['can_move_from'] == 1 );
    }

    /*!
     \return \c true if a node of class \a $classID can be moved to the current node by the current user.
     \sa checkAccess().
    */
    function canMoveTo( $classID = false )
    {
        if ( !isset( $this->Permissions['can_move_to'] ) )
        {
            $this->Permissions['can_move_to'] = $this->checkAccess( 'create', $classID );
        }
        return ( $this->Permissions['can_move_to'] == 1 );
    }

    /*!
     \return \c true if a node can be swaped by the current user.
     \sa checkAccess().
    */
    function canSwap()
    {
        if ( !isset( $this->Permissions['can_swap'] ) )
        {
            $this->Permissions['can_swap'] = $this->checkAccess( 'edit' );
        }
        return ( $this->Permissions['can_swap'] == 1 );
    }

    /*!
     \return \c true if current user can add object locations to current node.
     \sa checkAccess()
    */
    function canAddLocation()
    {
        if ( !isset( $this->Permissions['can_add_location'] ) )
        {
            $this->Permissions['can_add_location'] = $this->checkAccess( 'can_add_location' );
        }
        return ( $this->Permissions['can_add_location'] == 1 );
    }

    /*!
     \return \c true if current user can add object locations to current node.
    */
    function canRemoveLocation()
    {
        if ( !isset( $this->Permissions['can_remove_location'] ) )
        {
            $this->Permissions['can_remove_location'] = $this->checkAccess( 'can_remove_location' );
        }
        return ( $this->Permissions['can_remove_location'] == 1 );
    }

    /*!
     \static
     \returns the sort key for the given classAttributeID.
      int|string is returend. False is returned if unsuccessful.
    */
    static function sortKeyByClassAttributeID( $classAttributeID )
    {
        return eZContentClassAttribute::sortKeyTypeByID( $classAttributeID );
    }

    /*!
     \static
    */
    static function dataTypeByClassAttributeID( $classAttributeID )
    {
        return eZContentClassAttribute::dataTypeByID( $classAttributeID );
    }


    /*!
     Fetches the number of nodes which exists in the system.
    */
    static function fetchListCount()
    {
        $sql = "SELECT count( node_id ) as count FROM ezcontentobject_tree";
        $db = eZDB::instance();
        $rows = $db->arrayQuery( $sql );
        return $rows[0]['count'];
    }


    /*!
     Fetches a list of nodes and returns it. Offset and limitation can be set if needed.
    */
    static function fetchList( $asObject = true, $offset = false, $limit = false )
    {
        $sql = "SELECT * FROM ezcontentobject_tree";
        $parameters = array();
        if ( $offset !== false )
            $parameters['offset'] = $offset;
        if ( $limit !== false )
            $parameters['limit'] = $limit;
        $db = eZDB::instance();
        $rows = $db->arrayQuery( $sql, $parameters );
        $nodes = array();
        if ( $asObject )
        {
            foreach ( $rows as $row )
            {
                $nodes[] = new eZContentObjectTreeNode( $row );
            }
            return $nodes;
        }
        else
            return $rows;
    }

    /*!
        \a static
    */
    static function createSortingSQLStrings( $sortList, $treeTableName = 'ezcontentobject_tree', $allowCustomColumns = false )
    {
        $sortingInfo = array( 'sortCount'           => 0,
                              'sortingFields'       => " path_string ASC",
                              'attributeJoinCount'  => 0,
                              'attributeFromSQL'    => "",
                              'attributeTargetSQL'  => "",
                              'attributeWhereSQL'   => "" );

        if ( $sortList and is_array( $sortList ) and count( $sortList ) > 0 )
        {
            if ( count( $sortList ) > 1 and !is_array( $sortList[0] ) )
            {
                $sortList = array( $sortList );
            }

            $sortingFields      = '';
            $sortCount          = 0;
            $attributeJoinCount = 0;
            $stateJoinCount     = 0;
            $attributeFromSQL   = "";
            $attributeWhereSQL  = "";
            $datatypeSortingTargetSQL = "";

            foreach ( $sortList as $sortBy )
            {
                if ( is_array( $sortBy ) and count( $sortBy ) > 0 )
                {
                    if ( $sortCount > 0 )
                    {
                        $sortingFields .= ', ';
                    }

                    $sortField = $sortBy[0];
                    switch ( $sortField )
                    {
                        case 'path':
                        {
                            $sortingFields .= 'path_string';
                        } break;
                        case 'path_string':
                        {
                            $sortingFields .= 'path_identification_string';
                        } break;
                        case 'published':
                        {
                            $sortingFields .= 'ezcontentobject.published';
                        } break;
                        case 'modified':
                        {
                            $sortingFields .= 'ezcontentobject.modified';
                        } break;
                        case 'modified_subnode':
                        {
                            $sortingFields .= 'modified_subnode';
                        } break;
                        case 'section':
                        {
                            $sortingFields .= 'ezcontentobject.section_id';
                        } break;
                        case 'node_id':
                        {
                            $sortingFields .= $treeTableName . '.node_id';
                        } break;
                        case 'contentobject_id':
                        {
                            $sortingFields .= $treeTableName . '.contentobject_id';
                        } break;
                        case 'depth':
                        {
                            $sortingFields .= 'depth';
                        } break;
                        case 'class_identifier':
                        {
                            $sortingFields .= 'ezcontentclass.identifier';
                        } break;
                        case 'class_name':
                        {
                            $classNameFilter = eZContentClassName::sqlFilter();
                            $sortingFields .= 'contentclass_name';
                            $datatypeSortingTargetSQL .= ", $classNameFilter[nameField] AS contentclass_name";
                            $attributeFromSQL .= ", $classNameFilter[from]";
                            $attributeWhereSQL .= "$classNameFilter[where] AND ";
                        } break;
                        case 'priority':
                        {
                            $sortingFields .= $treeTableName . '.priority';
                        } break;
                        case 'name':
                        {
                            $sortingFields .= 'ezcontentobject_name.name';
                        } break;
                        case 'attribute':
                        {
                            $classAttributeID = $sortBy[2];
                            if ( !is_numeric( $classAttributeID ) )
                                $classAttributeID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $classAttributeID );


                            $contentAttributeTableAlias = "a$attributeJoinCount";
                            $datatypeFromSQL = "ezcontentobject_attribute $contentAttributeTableAlias";
                            $datatypeWhereSQL = "
                                   $contentAttributeTableAlias.contentobject_id = ezcontentobject.id AND
                                   $contentAttributeTableAlias.contentclassattribute_id = $classAttributeID AND
                                   $contentAttributeTableAlias.version = ezcontentobject_name.content_version AND";
                            $datatypeWhereSQL .= eZContentLanguage::sqlFilter( $contentAttributeTableAlias, 'ezcontentobject' );

                            $dataType = eZDataType::create( eZContentObjectTreeNode::dataTypeByClassAttributeID( $classAttributeID ) );
                            if( is_object( $dataType ) && $dataType->customSorting() )
                            {
                                $params = array();
                                $params['contentobject_attr_id'] = "$contentAttributeTableAlias.id";
                                $params['contentobject_attr_version'] = "$contentAttributeTableAlias.version";
                                $params['table_alias_suffix'] = "$attributeJoinCount";

                                $sql = $dataType->customSortingSQL( $params );

                                $datatypeFromSQL .= ", {$sql['from']}";
                                $datatypeWhereSQL .= " AND {$sql['where']}";
                                $datatypeSortingFieldSQL = $sql['sorting_field'];
                                $datatypeSortingTargetSQL .= ', ' . $sql['sorting_field'];
                            }
                            else
                            {
                                // Look up datatype for standard sorting
                                $sortKeyType = eZContentObjectTreeNode::sortKeyByClassAttributeID( $classAttributeID );
                                switch ( $sortKeyType )
                                {
                                    case 'string':
                                        {
                                            $sortKey = 'sort_key_string';
                                        } break;

                                    case 'int':
                                    default:
                                        {
                                            $sortKey = 'sort_key_int';
                                        } break;
                                }

                                $datatypeSortingFieldSQL = "a$attributeJoinCount.$sortKey";
                                $datatypeSortingTargetSQL .= ', ' . $datatypeSortingFieldSQL;
                            }

                            $sortingFields .= "$datatypeSortingFieldSQL";
                            $attributeFromSQL .= ", $datatypeFromSQL";
                            $attributeWhereSQL .= "$datatypeWhereSQL AND ";

                            $attributeJoinCount++;
                        }break;

                        case 'state':
                        {
                            $stateGroupID = $sortBy[2];
                            if ( !is_numeric( $stateGroupID ) )
                            {
                                $stateGroup = eZContentObjectStateGroup::fetchByIdentifier( $stateGroupID );
                                if ( $stateGroup )
                                {
                                    $stateGroupID = $stateGroup->attribute( 'id' );
                                }
                                else
                                {
                                    eZDebug::writeError( "Unknown content object state group '$stateGroupID'" );
                                    continue 2;
                                }
                            }

                            $stateAlias = "s$stateJoinCount";
                            $stateLinkAlias = "sl$stateJoinCount";
                            $sortingFields .= "$stateAlias.priority";
                            $datatypeSortingTargetSQL .= ", $stateAlias.priority";
                            $attributeFromSQL .= ", ezcobj_state $stateAlias, ezcobj_state_link $stateLinkAlias";
                            $attributeWhereSQL .= "$stateLinkAlias.contentobject_id=$treeTableName.contentobject_id AND
                                                   $stateLinkAlias.contentobject_state_id=$stateAlias.id AND
                                                   $stateAlias.group_id=$stateGroupID AND ";
                        } break;

                        default:
                        {
                            if ( $allowCustomColumns )
                            {
                                $sortingFields .= $sortField;
                            }
                            else
                            {
                                eZDebug::writeWarning( 'Unknown sort field: ' . $sortField, __METHOD__ );
                                continue;
                            }
                        }
                    }
                    $sortOrder = true; // true is ascending
                    if ( isset( $sortBy[1] ) )
                        $sortOrder = $sortBy[1];
                    $sortingFields .= $sortOrder ? " ASC" : " DESC";
                    ++$sortCount;
                }
            }

            $sortingInfo['sortCount']           = $sortCount;
            $sortingInfo['sortingFields']       = $sortingFields;
            $sortingInfo['attributeTargetSQL'] = $datatypeSortingTargetSQL;
            $sortingInfo['attributeJoinCount']  = $attributeJoinCount;
            $sortingInfo['attributeFromSQL']    = $attributeFromSQL;
            $sortingInfo['attributeWhereSQL']   = $attributeWhereSQL;
        }

        return $sortingInfo;
    }

    /*!
        \a static
    */
    static function createClassFilteringSQLString( $classFilterType, &$classFilterArray )
    {
        // Check for class filtering
        $classCondition = '';

        if ( isset( $classFilterType ) &&
             ( $classFilterType == 'include' || $classFilterType == 'exclude' ) &&
             count( $classFilterArray ) > 0 )
        {
            $classCondition = '  ';
            $i = 0;
            $classCount = count( $classFilterArray );
            $classIDArray = array();
            foreach ( $classFilterArray as $classID )
            {
                $originalClassID = $classID;
                // Check if classes are recerenced by identifier
                if ( is_string( $classID ) && !is_numeric( $classID ) )
                {
                    $classID = eZContentClass::classIDByIdentifier( $classID );
                }
                if ( is_numeric( $classID ) )
                {
                    $classIDArray[] = $classID;
                }
                else
                {
                    eZDebugSetting::writeWarning( 'kernel-content-class', "Invalid class identifier in subTree() classfilterarray, classID : " . $originalClassID );
                }
            }

            if ( count( $classIDArray ) > 0  )
            {
                $classCondition .= " ezcontentobject.contentclass_id ";
                if ( $classFilterType == 'include' )
                    $classCondition .= " IN ";
                else
                    $classCondition .= " NOT IN ";

                $classIDString =  implode( ', ', $classIDArray );
                $classCondition .= ' ( ' . $classIDString . ' ) AND';
            }
            else
            {
                if ( count( $classIDArray ) == 0 and count( $classFilterArray ) > 0 and $classFilterType == 'include' )
                {
                    $classCondition = false;
                }
            }
        }

        return $classCondition;
    }

    /*!
        \a static
    */
    static function createExtendedAttributeFilterSQLStrings( &$extendedAttributeFilter )
    {
        $filter = array( 'tables'   => '',
                         'joins'    => '',
                         'columns'  => '',
                         'group_by' => '' );

        if ( $extendedAttributeFilter and count( $extendedAttributeFilter ) > 1 )
        {
            $extendedAttributeFilterID      = $extendedAttributeFilter['id'];
            $extendedAttributeFilterParams  = $extendedAttributeFilter['params'];
            $filterINI                      = eZINI::instance( 'extendedattributefilter.ini' );

            if ( !$filterINI->hasGroup( $extendedAttributeFilterID ) )
            {
                eZDebug::writeError( "Unable to find configuration for the extended attribute filter '$extendedAttributeFilterID', the filter will be ignored", __METHOD__ );
                return $filter;
            }

            $filterClassName    = $filterINI->variable( $extendedAttributeFilterID, 'ClassName' );
            $filterMethodName   = $filterINI->variable( $extendedAttributeFilterID, 'MethodName' );

            if ( $filterINI->hasVariable( $extendedAttributeFilterID, 'FileName' ) )
            {
                $filterFile = $filterINI->variable( $extendedAttributeFilterID, 'FileName' );

                if ( $filterINI->hasVariable( $extendedAttributeFilterID, 'ExtensionName' ) )
                {
                    $extensionName = $filterINI->variable( $extendedAttributeFilterID, 'ExtensionName' );
                    include_once( eZExtension::baseDirectory() . "/$extensionName/$filterFile" );
                }
                else
                {
                    include_once( $filterFile );
                }
            }

            if ( !class_exists( $filterClassName, true ) )
            {
                eZDebug::writeError( "Unable to find the PHP class '$filterClassName' associated with the extended attribute filter '$extendedAttributeFilterID', the filter will be ignored", __METHOD__ );
                return $filter;
            }

            $classObject        = new $filterClassName();
            $parameterArray     = array( $extendedAttributeFilterParams );

            $sqlResult          = call_user_func_array( array( $classObject, $filterMethodName ), $parameterArray );

            $filter['tables']   = $sqlResult['tables'];
            $filter['joins']    = $sqlResult['joins'];
            $filter['columns']  = isset( $sqlResult['columns'] ) ? $sqlResult['columns'] : '';

            if( isset( $sqlResult['group_by'] ) )
                $filter['group_by'] =  $sqlResult['group_by'];
        }

        return $filter;
    }

    /*!
        \a static
    */
    static function createMainNodeConditionSQLString( $mainNodeOnly )
    {
        // Main node check
        $mainNodeCondition = '';
        if ( isset( $mainNodeOnly ) && $mainNodeOnly === true )
        {
            $mainNodeCondition = 'ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id AND';
        }

        return $mainNodeCondition;
    }

    /*!
        \a static
    */
    static function createObjectNameFilterConditionSQLString( $filter )
    {
        if ( !$filter )
            return '';

        $db = eZDB::instance();
        if ( $filter == 'others' )
        {
            $alphabet = eZAlphabetOperator::fetchAlphabet();
            $sql = '';
            foreach ( $alphabet as $letter )
            {
                $sql .= " AND ezcontentobject.name NOT LIKE '". $db->escapeString( $letter ) . "%' ";
            }
            return $sql;
        }
        $objectNameFilterSQL =  " AND ezcontentobject.name LIKE '" . $db->escapeString( $filter ) ."%'";
        return $objectNameFilterSQL;
    }


    /*!
        \a static
    */
    static function createAttributeFilterSQLStrings( &$attributeFilter, &$sortingInfo = array( 'sortCount' => 0, 'attributeJoinCount' => 0 ) )
    {
        // Check for attribute filtering

        $filterSQL = array( 'from'    => '',
                            'where'   => '' );

        $totalAttributesFiltersCount = 0;
        $invalidAttributesFiltersCount = 0;

        if ( isset( $attributeFilter ) && $attributeFilter !== false )
        {
            if ( !is_array( $attributeFilter ) )
            {
                eZDebug::writeError( "\$attributeFilter needs to be an array", __METHOD__  );
                return $filterSQL;
            }

            $filterArray = $attributeFilter;

            // Check if first value of array is a string.
            // To check for and/or filtering
            $filterJoinType = 'AND';
            if ( is_string( $filterArray[0] ) )
            {
                if ( strtolower( $filterArray[0] ) == 'or' )
                {
                    $filterJoinType = 'OR';
                }
                else if ( strtolower( $filterArray[0] ) == 'and' )
                {
                    $filterJoinType = 'AND';
                }
                unset( $filterArray[0] );
            }

            $attibuteFilterJoinSQL = "";
            $filterCount = $sortingInfo['sortCount'];
            $justFilterCount = 0;

            $db = eZDB::instance();
            if ( is_array( $filterArray ) )
            {
                // Handle attribute filters and generate SQL
                $totalAttributesFiltersCount = count( $filterArray );

                foreach ( $filterArray as $filter )
                {
                    $isFilterValid = true; // by default assumes that filter is valid

                    $filterAttributeID = $filter[0];
                    $filterType = $filter[1];
                    $filterValue = is_array( $filter[2] ) ? '' : $db->escapeString( $filter[2] );

                    $useAttributeFilter = false;
                    switch ( $filterAttributeID )
                    {
                        case 'path':
                        {
                            $filterField = 'path_string';
                        } break;
                        case 'published':
                        {
                            $filterField = 'ezcontentobject.published';
                        } break;
                        case 'modified':
                        {
                            $filterField = 'ezcontentobject.modified';
                        } break;
                        case 'modified_subnode':
                        {
                            $filterField = 'modified_subnode';
                        } break;
                        case 'node_id':
                        {
                            $filterField = 'ezcontentobject_tree.node_id';
                        } break;
                        case 'contentobject_id':
                        {
                            $filterField = 'ezcontentobject_tree.contentobject_id';
                        } break;
                        case 'section':
                        {
                            $filterField = 'ezcontentobject.section_id';
                        } break;
                        case 'state':
                        {
                            // state only supports =, !=, in, and not_in
                            // other operators do not make any sense in this context
                            $hasFilterOperator = true;

                            switch ( $filterType )
                            {
                                case '=' :
                                case '!=':
                                {
                                    $subQueryCondition = 'contentobject_state_id = ' . (int) $filter[2];
                                    $filterOperator = ( $filterType == '=' ? 'IN' : 'NOT IN' );
                                } break;

                                case 'in':
                                case 'not_in' :
                                {
                                    if ( is_array( $filter[2] ) )
                                    {
                                        $subQueryCondition = $db->generateSQLINStatement( $filter[2], 'contentobject_state_id', false, false, 'int' );
                                        $filterOperator = ( $filterType == 'in' ? 'IN' : 'NOT IN' );
                                    }
                                    else
                                    {
                                        $hasFilterOperator = false;
                                    }
                                } break;

                                default :
                                {
                                    $hasFilterOperator = false;
                                    eZDebug::writeError( "Unknown attribute filter type for state: $filterType", __METHOD__ );
                                } break;
                            }

                            if ( $hasFilterOperator )
                            {
                                if ( ( $filterCount - $sortingInfo['sortCount'] ) > 0 )
                                    $attibuteFilterJoinSQL .= " $filterJoinType ";

                                $attibuteFilterJoinSQL .= "ezcontentobject.id $filterOperator (SELECT contentobject_id FROM ezcobj_state_link WHERE $subQueryCondition)";

                                $filterCount++;
                                $justFilterCount++;
                            }

                            continue 2;
                        } break;
                        case 'depth':
                        {
                            $filterField = 'depth';
                        } break;
                        case 'class_identifier':
                        {
                            $filterField = 'ezcontentclass.identifier';
                        } break;
                        case 'class_name':
                        {
                            $classNameFilter = eZContentClassName::sqlFilter();
                            $filterField = $classNameFilter['nameField'];
                            $filterSQL['from'] .= ", $classNameFilter[from]";
                            $filterSQL['where'] .= "$classNameFilter[where] AND ";
                        } break;
                        case 'priority':
                        {
                            $filterField = 'ezcontentobject_tree.priority';
                        } break;
                        case 'name':
                        {
                            $filterField = 'ezcontentobject_name.name';
                        } break;
                        case 'owner':
                        {
                            $filterField = 'ezcontentobject.owner_id';
                        } break;
                        case 'visibility':
                        {
                            $filterValue = ( $filterValue == '1' ) ? 0 : 1;
                            $filterField = 'ezcontentobject_tree.is_invisible';
                        } break;
                        default:
                        {
                            $useAttributeFilter = true;
                        } break;
                    }

                    if ( $useAttributeFilter )
                    {
                        if ( !is_numeric( $filterAttributeID ) )
                            $filterAttributeID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $filterAttributeID );

                        if ( $filterAttributeID === false )
                        {
                            $isFilterValid = false;
                            if( $filterJoinType === 'AND' )
                            {
                                // go out
                                $invalidAttributesFiltersCount = $totalAttributesFiltersCount;
                                break;
                            }

                            ++$invalidAttributesFiltersCount;
                        }
                        else
                        {
                            // Check datatype for filtering
                            $filterDataType = eZContentObjectTreeNode::sortKeyByClassAttributeID( $filterAttributeID );
                            if ( $filterDataType === false )
                            {
                                $isFilterValid = false;
                                if( $filterJoinType === 'AND' )
                                {
                                    // go out
                                    $invalidAttributesFiltersCount = $totalAttributesFiltersCount;
                                    break;
                                }

                                // check next filter
                                ++$invalidAttributesFiltersCount;
                            }
                            else
                            {
                                $sortKey = false;
                                if ( $filterDataType == 'string' )
                                {
                                    $sortKey = 'sort_key_string';
                                }
                                else
                                {
                                    $sortKey = 'sort_key_int';
                                }

                                $filterField = "a$filterCount.$sortKey";

                                // Use the same joins as we do when sorting,
                                // if more attributes are filtered by we will append them
                                if ( $filterCount >= $sortingInfo['attributeJoinCount'] )
                                {
                                    $filterSQL['from']  .= ", ezcontentobject_attribute a$filterCount ";
                                }

                                $filterSQL['where'] .= "
                                  a$filterCount.contentobject_id = ezcontentobject.id AND
                                  a$filterCount.contentclassattribute_id = $filterAttributeID AND
                                  a$filterCount.version = ezcontentobject_name.content_version AND ";
                                $filterSQL['where'] .= eZContentLanguage::sqlFilter( "a$filterCount", 'ezcontentobject' ). ' AND ';
                            }
                        }
                    }

                    if ( $isFilterValid )
                    {
                        $hasFilterOperator = true;
                        // Controls quotes around filter value, some filters do this manually
                        $noQuotes = false;
                        // Controls if $filterValue or $filter[2] is used, $filterValue is already escaped
                        $unEscape = false;

                        switch ( $filterType )
                        {
                            case '=' :
                            {
                                $filterOperator = '=';
                            }break;

                            case '!=' :
                            {
                                $filterOperator = '<>';
                            }break;

                            case '>' :
                            {
                                $filterOperator = '>';
                            }break;

                            case '<' :
                            {
                                $filterOperator = '<';
                            }break;

                            case '<=' :
                            {
                                $filterOperator = '<=';
                            }break;

                            case '>=' :
                            {
                                $filterOperator = '>=';
                            }break;

                            case 'like':
                            case 'not_like':
                            {
                                $filterOperator = ( $filterType == 'like' ? 'LIKE' : 'NOT LIKE' );
                                // We escape the string ourselves, this MUST be done before wildcard replace
                                $filter[2] = $db->escapeString( $filter[2] );
                                $unEscape = true;
                                // Since * is used as wildcard we need to transform the string to
                                // use % as wildcard. The following rules apply:
                                // - % -> \%
                                // - * -> %
                                // - \* -> *
                                // - \\ -> \

                                $filter[2] = preg_replace( array( '#%#m',
                                                                  '#(?<!\\\\)\\*#m',
                                                                  '#(?<!\\\\)\\\\\\*#m',
                                                                  '#\\\\\\\\#m' ),
                                                           array( '\\%',
                                                                  '%',
                                                                  '*',
                                                                  '\\\\' ),
                                                           $filter[2] );
                            } break;

                            case 'in':
                            case 'not_in' :
                            {
                                $filterOperator = ( $filterType == 'in' ? 'IN' : 'NOT IN' );
                                // Turn off quotes for value, we do this ourselves
                                $noQuotes = true;
                                if ( is_array( $filter[2] ) )
                                {
                                    reset( $filter[2] );
                                    while ( list( $key, $value ) = each( $filter[2] ) )
                                    {
                                        // Non-numerics must be escaped to avoid SQL injection
                                        $filter[2][$key] = is_numeric( $value ) ? $value : "'" . $db->escapeString( $value ) . "'";
                                    }
                                    $filterValue = '(' .  implode( ",", $filter[2] ) . ')';
                                }
                                else
                                {
                                    $hasFilterOperator = false;
                                }
                            } break;

                            case 'between':
                            case 'not_between' :
                            {
                                $filterOperator = ( $filterType == 'between' ? 'BETWEEN' : 'NOT BETWEEN' );
                                // Turn off quotes for value, we do this ourselves
                                $noQuotes = true;
                                if ( is_array( $filter[2] ) )
                                {
                                    // Check for non-numerics to avoid SQL injection
                                    if ( !is_numeric( $filter[2][0] ) )
                                        $filter[2][0] = "'" . $db->escapeString( $filter[2][0] ) . "'";
                                    if ( !is_numeric( $filter[2][1] ) )
                                        $filter[2][1] = "'" . $db->escapeString( $filter[2][1] ) . "'";

                                    $filterValue = $filter[2][0] . ' AND ' . $filter[2][1];
                                }
                            } break;

                            default :
                            {
                                $hasFilterOperator = false;
                                eZDebug::writeError( "Unknown attribute filter type: $filterType", __METHOD__ );
                            }break;

                        }
                        if ( $hasFilterOperator )
                        {
                            if ( ( $filterCount - $sortingInfo['sortCount'] ) > 0 )
                                $attibuteFilterJoinSQL .= " $filterJoinType ";

                            // If $unEscape is true we get the filter value from the 2nd element instead
                            // which must have been escaped by filter type
                            $filterValue = $unEscape ? $filter[2] : $filterValue;

                            $attibuteFilterJoinSQL .= "$filterField $filterOperator ";
                            $attibuteFilterJoinSQL .= $noQuotes ? "$filterValue " : "'$filterValue' ";

                            $filterCount++;
                            $justFilterCount++;
                        }
                    }
                } // end of 'foreach ( $filterArray as $filter )'

                if ( $totalAttributesFiltersCount == $invalidAttributesFiltersCount )
                {
                    eZDebug::writeNotice( "Attribute filter returned false" );
                    $filterSQL = false;
                }
                else
                {
                    if ( $justFilterCount > 0 )
                        $filterSQL['where'] .= "                            ( " . $attibuteFilterJoinSQL . " ) AND ";
                }
            } // end of 'if ( is_array( $filterArray ) )'
        }

        return $filterSQL;
    }

    /*!
        \a static
    */
    static function createNotEqParentSQLString( $nodeID, $depth = false, $depthOperator = 'le' )
    {
        $notEqParentString  = '';
        if( !$depth || $depthOperator == 'le' || $depthOperator == 'lt' )
        {
            $notEqParentString  = "ezcontentobject_tree.node_id != $nodeID AND";
        }

        return $notEqParentString;
    }

    /*!
        \a static
    */
    static function createPathConditionSQLString( $nodePath, $nodeDepth, $depth = false, $depthOperator = 'le' )
    {
        $pathCondition  = '';
        $depthCondition = '';

        if ( $depth )
        {
            $sqlDepthOperator = '<=';
            if ( $depthOperator )
            {
                if ( $depthOperator == 'lt' )
                {
                    $sqlDepthOperator = '<';
                }
                else if ( $depthOperator == 'gt' )
                {
                    $sqlDepthOperator = '>';
                }
                else if ( $depthOperator == 'le' )
                {
                    $sqlDepthOperator = '<=';
                }
                else if ( $depthOperator == 'ge' )
                {
                    $sqlDepthOperator = '>=';
                }
                else if ( $depthOperator == 'eq' )
                {
                    $sqlDepthOperator = '=';
                }
            }

            $nodeDepth += $depth;
            $depthCondition = ' ezcontentobject_tree.depth '. $sqlDepthOperator . ' ' . $nodeDepth . '  and ';
        }

        $pathCondition = " ezcontentobject_tree.path_string like '$nodePath%' and $depthCondition ";
        return $pathCondition;
    }

    /*!
        \a static
    */
    static function createPathConditionAndNotEqParentSQLStrings( &$outPathConditionStr, &$outNotEqParentStr, $nodeID, $depth = false, $depthOperator = 'le' )
    {
        if ( !$depthOperator )
        {
            $depthOperator = 'le';
        }

        // check if we are only fetching children
        // - depth (lower than or) eqaul to 1
        // - depth lower than 2 = depth equal to 1
        $onlyChildren = ( $depth === 1 && ( $depthOperator === 'le' || $depthOperator === 'eq'  ) ) ||
                        ( $depth === 2 && $depthOperator === 'lt' );

        if ( is_array( $nodeID ) && count( $nodeID ) == 1 )
        {
            $nodeID = $nodeID[0];
        }

        if ( is_array( $nodeID ) )
        {
            $outNotEqParentStr = '';

            // a parent_node_id condition suffits when only fetching children
            if ( $onlyChildren )
            {
                $db = eZDB::instance();
                $outPathConditionStr = $db->generateSQLINStatement( $nodeID, 'ezcontentobject_tree.parent_node_id', false, true, 'int' ) . ' and';
            }
            else
            {
                $nodeIDList             = $nodeID;
                $sqlPartForOneNodeList  = array();

                foreach ( $nodeIDList as $nodeID )
                {
                    $node           = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                    if ( !is_array( $node ) )
                        return false;

                    $nodePath       = $node['path_string'];
                    $nodeDepth      = $node['depth'];
                    $depthCond      = '';
                    if ( $depth )
                    {
                        $sqlDepthOperator = '<=';
                        if ( $depthOperator )
                        {
                            if ( $depthOperator == 'lt' )
                            {
                                $sqlDepthOperator = '<';
                            }
                            else if ( $depthOperator == 'gt' )
                            {
                                $sqlDepthOperator = '>';
                            }
                            else if ( $depthOperator == 'le' )
                            {
                                $sqlDepthOperator = '<=';
                            }
                            else if ( $depthOperator == 'ge' )
                            {
                                $sqlDepthOperator = '>=';
                            }
                            else if ( $depthOperator == 'eq' )
                            {
                                $sqlDepthOperator = '=';
                            }
                        }
                        $nodeDepth += $depth;
                        $depthCond = ' and ezcontentobject_tree.depth '. $sqlDepthOperator . ' ' . $nodeDepth . ' ';
                    }

                    $requireNotEqParentStr      = !$depth || $depthOperator == 'le' || $depthOperator == 'lt';
                    $notEqParentStr             = $requireNotEqParentStr ? " and ezcontentobject_tree.node_id != $nodeID " : '';
                    $sqlPartForOneNodeList[]    = " ( ezcontentobject_tree.path_string like '$nodePath%'   $depthCond $notEqParentStr ) ";
                }
                $outPathConditionStr = implode( ' or ', $sqlPartForOneNodeList );
                $outPathConditionStr = ' (' . $outPathConditionStr . ') and';
            }
        }
        else
        {
            if ( $nodeID == 0 )
            {
                return false;
            }

            // a parent_node_id condition suffits when only fetching children
            if ( $onlyChildren )
            {
                $outNotEqParentStr = '';
                $outPathConditionStr = 'ezcontentobject_tree.parent_node_id = ' . (int) $nodeID . ' and';
            }
            else
            {
                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                if ( !is_array( $node ) )
                    return false;

                $nodePath   = $node['path_string'];
                $nodeDepth  = $node['depth'];

                $outNotEqParentStr   = eZContentObjectTreeNode::createNotEqParentSQLString( $nodeID, $depth, $depthOperator );
                $outPathConditionStr = eZContentObjectTreeNode::createPathConditionSQLString( $nodePath, $nodeDepth, $depth, $depthOperator );
            }
        }

        return true;
    }

    /*!
        \a static
    */
    static function createGroupBySQLStrings( &$outGroupBySelectText, &$outGroupByText, $groupBy )
    {
        if ( $groupBy )
        {
            if ( isset( $groupBy['field'] ) and isset( $groupBy['type'] ) )
            {
                $groupByField       = $groupBy['field'];
                $groupByFieldType   = $groupBy['type'];

                switch ( $groupByField )
                {
                    case 'published':
                    {
                        $groupBySelect = eZContentObjectTreeNode::subTreeGroupByDateField( "ezcontentobject." . $groupByField, $groupByFieldType );
                        $groupBySelect['field'] = "ezcontentobject." . $groupByField;
                    } break;
                    case 'modified':
                    {
                        $groupBySelect = eZContentObjectTreeNode::subTreeGroupByDateField( "ezcontentobject." . $groupByField, $groupByFieldType );
                        $groupBySelect['field'] = "ezcontentobject." . $groupByField;
                    } break;
                }

                $outGroupBySelectText = ", " . $groupBySelect['select'];
                $outGroupByText = "GROUP BY " . $groupBySelect['group_field'];
            }
        }
    }

    /*!
        \a static
    */
    static function createVersionNameTablesSQLString( $useVersionName )
    {
        $versionNameTables = '';

        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
        }

        return $versionNameTables;
    }

    /*!
        \a static
    */
    static function createVersionNameTargetsSQLString( $useVersionName )
    {
        $versionNameTargets = '';

        if ( $useVersionName )
        {
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';
        }


        return $versionNameTargets;
    }

    /*!
        \a static
    */
    static function createVersionNameJoinsSQLString( $useVersionName, $includeAnd = true, $onlyTranslated = false, $lang = false, $treeTableName = 'ezcontentobject_tree' )
    {
        $versionNameJoins = '';
        if ( $useVersionName )
        {
            if ( $includeAnd )
            {
                $versionNameJoins .= ' AND ';
            }
            $versionNameJoins .= " $treeTableName.contentobject_id = ezcontentobject_name.contentobject_id and
                                   $treeTableName.contentobject_version = ezcontentobject_name.content_version and ";
            $versionNameJoins .= eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject' );
        }
        return $versionNameJoins;
    }

    /*!
        \a static
        Deprecated. Use 'createPermissionCheckingSQL' instead.
    */
    static function createPermissionCheckingSQLString( $limitationList )
    {
        $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );
        return $sqlPermissionChecking['where'];
    }

    /*!
        \a static
    */
    static function createPermissionCheckingSQL( $limitationList, $treeTableName = 'ezcontentobject_tree', $tableAliasName = 'ezcontentobject_tree' )
    {
        $db = eZDB::instance();

        $sqlPermissionCheckingFrom = '';
        $sqlPermissionCheckingWhere = '';
        $sqlPermissionTempTables = array();
        $groupPermTempTable = false;
        $createdStateAliases = array();

        if ( is_array( $limitationList ) && count( $limitationList ) > 0 )
        {
            $sqlParts = array();
            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();
                $sqlPartPartPart = array();
                $sqlPlacementPart = array();

                foreach ( array_keys( $limitationArray ) as $ident )
                {
                    switch( $ident )
                    {
                        case 'Class':
                        {
                            $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Section':
                        case 'User_Section':
                        {
                            $sqlPartPart[] = 'ezcontentobject.section_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Owner':
                        {
                            $user = eZUser::currentUser();
                            $userID = $user->attribute( 'contentobject_id' );
                            $sqlPartPart[] = "ezcontentobject.owner_id = '" . $db->escapeString( $userID ) . "'";
                        } break;

                        case 'Group':
                        {
                            if ( !$groupPermTempTable )
                            {
                                $user = eZUser::currentUser();
                                $userContentObject = $user->attribute( 'contentobject' );
                                $parentList = $userContentObject->attribute( 'parent_nodes' );

                                $groupPermTempTable = $db->generateUniqueTempTableName( 'ezgroup_perm_tmp_%' );
                                $sqlPermissionTempTables[] = $groupPermTempTable;

                                $db->createTempTable( "CREATE TEMPORARY TABLE $groupPermTempTable ( user_id int NOT NULL PRIMARY KEY )" );
                                $db->query( "INSERT INTO $groupPermTempTable
                                                    SELECT DISTINCT contentobject_id AS user_id
                                                    FROM     ezcontentobject_tree
                                                    WHERE    parent_node_id IN ("  . implode( ', ', $parentList ) . ')',
                                            eZDBInterface::SERVER_SLAVE );

                                $sqlPermissionCheckingFrom .= ', ' . $groupPermTempTable;
                            }
                            $sqlPartPart[] = "ezcontentobject.owner_id = $groupPermTempTable.user_id";
                        } break;

                        case 'Node':
                        {
                            $sqlPlacementPart[] = $tableAliasName . '.node_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Subtree':
                        {
                            $sqlSubtreePart = array();
                            foreach ( $limitationArray[$ident] as $limitationPathString )
                            {
                                $sqlSubtreePart[] = "$tableAliasName.path_string like '$limitationPathString%'";
                            }
                            $sqlPlacementPart[] = implode( ' OR ', $sqlSubtreePart );
                        } break;

                        case 'User_Subtree':
                        {
                            $sqlPartUserSubtree = array();
                            foreach ( $limitationArray[$ident] as $limitationPathString )
                            {
                                $sqlPartUserSubtree[] = "$tableAliasName.path_string like '$limitationPathString%'";
                            }
                            $sqlPartPart[] = implode( ' OR ', $sqlPartUserSubtree );
                        } break;

                        default:
                        {
                            if ( strncmp( $ident, 'StateGroup_', 11 ) === 0 )
                            {
                                $stateIdentifier = substr( $ident, 11 );

                                if ( !isset( $createdStateAliases[$stateIdentifier] ) )
                                {
                                    $stateIndex = count( $createdStateAliases );
                                }
                                else
                                {
                                    $stateIndex = $createdStateAliases[$stateIdentifier];
                                }
                                $stateTable = "ezcobj_state_{$stateIndex}_perm";

                                if ( !isset( $createdStateAliases[$stateIdentifier] ) )
                                {
                                    $createdStateAliases[$stateIdentifier] = $stateIndex;
                                    $stateLinkTable =  "ezcobj_state_lnk_{$stateIndex}_perm";
                                    $stateGroupTable = "ezcobj_state_grp_{$stateIndex}_perm";
                                    $stateAliasTables[$stateIdentifier] = $stateTable;

                                    $sqlPermissionCheckingFrom .= ", ezcobj_state_link $stateLinkTable ";
                                    $sqlPermissionCheckingFrom .= ", ezcobj_state_group $stateGroupTable ";
                                    $sqlPermissionCheckingFrom .= ", ezcobj_state $stateTable ";

                                    $sqlPermissionCheckingWhere .= "AND $stateLinkTable.contentobject_id = ezcontentobject.id " .
                                                                   "AND $stateTable.id = $stateLinkTable.contentobject_state_id " .
                                                                   "AND $stateTable.group_id = $stateGroupTable.id " .
                                                                   "AND $stateGroupTable.identifier='" . $db->escapeString( $stateIdentifier ) . "' ";
                                }

                                if ( count( $limitationArray[$ident] ) > 1 )
                                {
                                    $sqlPartPart[] = $db->generateSQLINStatement( $limitationArray[$ident], "$stateTable.id" );
                                }
                                else
                                {
                                    $sqlPartPart[] = "$stateTable.id = " . $limitationArray[$ident][0];
                                }
                            }
                        }
                    }
                }
                if ( $sqlPlacementPart )
                {
                    $sqlPartPart[] = '( ( ' . implode( ' ) OR ( ', $sqlPlacementPart ) . ' ) )';
                }
                if ( $sqlPartPartPart )
                {
                    $sqlPartPart[] = '( ' . implode( ' ) OR ( ', $sqlPartPartPart ) . ' )';
                }
                $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }
            $sqlPermissionCheckingWhere .= ' AND ((' . implode( ") OR (", $sqlParts ) . ')) ';
        }

        $sqlPermissionChecking = array( 'from' => $sqlPermissionCheckingFrom,
                                        'where' => $sqlPermissionCheckingWhere,
                                        'temp_tables' => $sqlPermissionTempTables );

        return $sqlPermissionChecking;
    }


    /*!
        \a static

        \param $limit maximum number of nodes in the path to use, starting from last node
    */
    static function createNodesConditionSQLStringFromPath( $nodePath, $includingLastNodeInThePath, $limit = false )
    {
        $pathString = false;
        $pathArray  = explode( '/', trim( $nodePath, '/' ) );

        $pathArrayCount = count( $pathArray );

        if ( $limit && $includingLastNodeInThePath == false )
        {
            $limit++;
        }

        $sliceOffset = $limit && $pathArrayCount > $limit ? $pathArrayCount - $limit : 0;
        $sliceLength = $includingLastNodeInThePath ? $pathArrayCount - $sliceOffset : $pathArrayCount - ( $sliceOffset + 1 );

        // only take a slice when necessary
        if ( ( $sliceOffset + $sliceLength ) < $pathArrayCount )
        {
            $pathArray = array_slice( $pathArray, $sliceOffset, $sliceLength );
        }

        if ( $sliceLength == 1 )
        {
            $pathString = ' node_id = ' . implode( '', $pathArray ) . ' and ';
        }
        else if ( $sliceLength > 0 )
        {
            $db = eZDB::instance();
            $pathString = ' ' . $db->generateSQLINStatement( $pathArray, 'node_id' ) . ' and ';
        }

        return $pathString;
    }

    /*!
        \a static
        If \a $useSettings is true \a $fetchHidden will be ignored.
        If \a $useSettings is false \a $fetchHidden will be used.
    */
    static function createShowInvisibleSQLString( $useSettings, $fetchHidden = true )
    {
        $showInvisibleNodesCond = '';
        $showInvisible          = $fetchHidden;

        if ( $useSettings )
            $showInvisible = eZContentObjectTreeNode::showInvisibleNodes();

        if ( !$showInvisible )
            $showInvisibleNodesCond = 'AND ezcontentobject_tree.is_invisible = 0';

        return $showInvisibleNodesCond;
    }

    /*!
     \a static
     \returns true if we should show invisible nodes (determined by ini setting), false otherwise.
    */
    static function showInvisibleNodes()
    {
        static $cachedResult;

        if ( !isset( $cachedResult ) )
        {
            $ini = eZINI::instance( 'site.ini' );
            $cachedResult = $ini->hasVariable( 'SiteAccessSettings', 'ShowHiddenNodes' ) ?
                            $ini->variable( 'SiteAccessSettings', 'ShowHiddenNodes' ) == 'true' :
                            true;
        }

        return $cachedResult;
    }

    /*!
        \a static
    */
    static function getLimitationList( &$limitation )
    {
        $currentUser = eZUser::currentUser();
        $currentUserID = $currentUser->attribute( 'contentobject_id' );
        $limitationList = array();

        if ( $limitation !== false )
        {
            $limitationList = $limitation;
        }
        else if ( isset( $GLOBALS['ezpolicylimitation_list'][$currentUserID]['content']['read'] ) )
        {
            $limitationList =& $GLOBALS['ezpolicylimitation_list'][$currentUserID]['content']['read'];
            eZDebugSetting::writeDebug( 'kernel-content-treenode', $limitationList, "limitation list"  );
        }
        else
        {
            $accessResult = $currentUser->hasAccessTo( 'content', 'read' );

            if ( $accessResult['accessWord'] == 'no' )
            {
                $limitationList = false;
                $GLOBALS['ezpolicylimitation_list'][$currentUserID]['content']['read'] = false;
            }
            else if ( $accessResult['accessWord'] == 'limited' )
            {
                $limitationList = $accessResult['policies'];
                $GLOBALS['ezpolicylimitation_list'][$currentUserID]['content']['read'] = $accessResult['policies'];
            }
        }

        return $limitationList;
    }

    /*!
     \sa subTree
    */
    static function subTreeByNodeID( $params = false, $nodeID = 0 )
    {
        if ( !is_numeric( $nodeID ) and !is_array( $nodeID ) )
        {
            return null;
        }

        if ( $params === false )
        {
            $params = array( 'Depth'                    => false,
                             'Offset'                   => false,
                             //'OnlyTranslated'           => false,
                             'Language'                 => false,
                             'Limit'                    => false,
                             'SortBy'                   => false,
                             'AttributeFilter'          => false,
                             'ExtendedAttributeFilter'  => false,
                             'ClassFilterType'          => false,
                             'ClassFilterArray'         => false,
                             'GroupBy'                  => false );
        }

        $offset           = ( isset( $params['Offset'] ) && is_numeric( $params['Offset'] ) ) ? $params['Offset']             : false;
        //$onlyTranslated   = ( isset( $params['OnlyTranslated']      ) )                       ? $params['OnlyTranslated']     : false;
        $language         = ( isset( $params['Language']      ) )                             ? $params['Language']           : false;
        $limit            = ( isset( $params['Limit']  ) && is_numeric( $params['Limit']  ) ) ? $params['Limit']              : false;
        $depth            = ( isset( $params['Depth']  ) && is_numeric( $params['Depth']  ) ) ? $params['Depth']              : false;
        $depthOperator    = ( isset( $params['DepthOperator']     ) )                         ? $params['DepthOperator']      : false;
        $asObject         = ( isset( $params['AsObject']          ) )                         ? $params['AsObject']           : true;
        $loadDataMap      = ( isset( $params['LoadDataMap'] ) )                               ? $params['LoadDataMap']        : false;
        $groupBy          = ( isset( $params['GroupBy']           ) )                         ? $params['GroupBy']            : false;
        $mainNodeOnly     = ( isset( $params['MainNodeOnly']      ) )                         ? $params['MainNodeOnly']       : false;
        $ignoreVisibility = ( isset( $params['IgnoreVisibility']  ) )                         ? $params['IgnoreVisibility']   : false;
        $objectNameFilter = ( isset( $params['ObjectNameFilter']  ) )                         ? $params['ObjectNameFilter']   : false;

        if ( $offset < 0 )
        {
            $offset = abs( $offset );
        }

        if ( !isset( $params['SortBy'] ) )
            $params['SortBy'] = false;
        if ( !isset( $params['ClassFilterType'] ) )
            $params['ClassFilterType'] = false;

        $allowCustomSorting = false;
        if ( isset( $params['ExtendedAttributeFilter'] ) && is_array ( $params['ExtendedAttributeFilter'] ) )
        {
            $allowCustomSorting = true;
        }

        $sortingInfo             = eZContentObjectTreeNode::createSortingSQLStrings( $params['SortBy'], 'ezcontentobject_tree', $allowCustomSorting );
        $classCondition          = eZContentObjectTreeNode::createClassFilteringSQLString( $params['ClassFilterType'], $params['ClassFilterArray'] );
        if ( $classCondition === false )
        {
            eZDebug::writeNotice( "Class filter returned false" );
            return null;
        }

        $attributeFilter         = eZContentObjectTreeNode::createAttributeFilterSQLStrings( $params['AttributeFilter'], $sortingInfo );
        if ( $attributeFilter === false )
        {
            return null;
        }
        $extendedAttributeFilter = eZContentObjectTreeNode::createExtendedAttributeFilterSQLStrings( $params['ExtendedAttributeFilter'] );
        $mainNodeOnlyCond        = eZContentObjectTreeNode::createMainNodeConditionSQLString( $mainNodeOnly );

        $pathStringCond     = '';
        $notEqParentString  = '';
        // If the node(s) doesn't exist we return null.
        if ( !eZContentObjectTreeNode::createPathConditionAndNotEqParentSQLStrings( $pathStringCond, $notEqParentString, $nodeID, $depth, $depthOperator ) )
        {
            return null;
        }

        if ( $language )
        {
            if ( !is_array( $language ) )
            {
                $language = array( $language );
            }
            // This call must occur after eZContentObjectTreeNode::createPathConditionAndNotEqParentSQLStrings,
            // because the parent node may not exist in Language
            eZContentLanguage::setPrioritizedLanguages( $language );
        }

        $groupBySelectText  = '';
        $groupBySQL         = $extendedAttributeFilter['group_by'];
        if ( !$groupBySQL )
        {
            eZContentObjectTreeNode::createGroupBySQLStrings( $groupBySelectText, $groupBySQL, $groupBy );
        }
        else if ( $groupBy )
        {
            eZDebug::writeError( "Cannot use group_by parameter together with extended attribute filter which sets group_by!", __METHOD__ );
        }


        $useVersionName     = true;

        $versionNameTables  = eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
        $versionNameTargets = eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
        $versionNameJoins   = eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName, false );

        $languageFilter = ' AND ' . eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

        if ( $language )
        {
            eZContentLanguage::clearPrioritizedLanguages();
        }
        $objectNameFilterSQL = eZContentObjectTreeNode::createObjectNameFilterConditionSQLString( $objectNameFilter );

        $limitation = ( isset( $params['Limitation']  ) && is_array( $params['Limitation']  ) ) ? $params['Limitation']: false;
        $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
        $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );

        // Determine whether we should show invisible nodes.
        $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( !$ignoreVisibility );

        $query = "SELECT DISTINCT
                       ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.serialized_name_list as class_serialized_name_list,
                       ezcontentclass.identifier as class_identifier,
                       ezcontentclass.is_container as is_container
                       $groupBySelectText
                       $versionNameTargets
                       $sortingInfo[attributeTargetSQL]
                       $extendedAttributeFilter[columns]
                   FROM
                      ezcontentobject_tree,
                      ezcontentobject,ezcontentclass
                      $versionNameTables
                      $sortingInfo[attributeFromSQL]
                      $attributeFilter[from]
                      $extendedAttributeFilter[tables]
                      $sqlPermissionChecking[from]
                   WHERE
                      $pathStringCond
                      $extendedAttributeFilter[joins]
                      $sortingInfo[attributeWhereSQL]
                      $attributeFilter[where]
                      ezcontentclass.version=0 AND
                      $notEqParentString
                      ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                      ezcontentclass.id = ezcontentobject.contentclass_id AND
                      $mainNodeOnlyCond
                      $classCondition
                      $versionNameJoins
                      $showInvisibleNodesCond
                      $sqlPermissionChecking[where]
                      $objectNameFilterSQL
                      $languageFilter
                $groupBySQL";

        if ( $sortingInfo['sortingFields'] )
            $query .= " ORDER BY $sortingInfo[sortingFields]";

        $db = eZDB::instance();

        $server = count( $sqlPermissionChecking['temp_tables'] ) > 0 ? eZDBInterface::SERVER_SLAVE : false;

        $nodeListArray = $db->arrayQuery( $query, array( 'offset' => $offset,
                                                         'limit'  => $limit ),
                                                  $server );

        if ( $asObject )
        {
            $retNodeList = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
            if ( $loadDataMap === true )
                eZContentObject::fillNodeListAttributes( $retNodeList );
            else if ( $loadDataMap && is_numeric( $loadDataMap ) && $loadDataMap >= count( $retNodeList ) )
                eZContentObject::fillNodeListAttributes( $retNodeList );
        }
        else
        {
            $retNodeList = $nodeListArray;
        }

        // cleanup temp tables
        $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );

        return $retNodeList;
    }

    function subTree( $params = false )
    {
        return eZContentObjectTreeNode::subTreeByNodeID( $params, $this->attribute( 'node_id' ) );
    }

    /*!
    Retrieve subtrees from multiple paths.

    This method composes a list of objects retrieved from various node paths,
    sorted by criteria that are globally applied to the whole list.

    It is for example useful for an RSS feed that serves content from
    several node paths. The respective subtrees need to be amalgated and
    the resulting object listed sorted by publishing date to show the latest
    entries in chronological order.

    The first parameter is a multi-dimensional array containing the
    node IDs and filter criteria assigned to each of the nodes:

    array(
        [node_1] => array(
                        'ClassFilterType' =>    [filter_type],
                        'ClassFilterArray'  =>  [filter_array]
                        ),
         [node_2] => array(
                        'ClassFilterType' =>    [filter_type],
                        'ClassFilterArray'  =>  [filter_array]
                        )
         )

    The second parameter is a single-dimensional array with criteria
    applied to the list of objects retrieved from the various subtrees:

    array(
        'SortBy' => [sorting-criteria]
        )
    */
    static function subTreeMultiPaths( $nodesParams, $listParams = NULL )
    {
        if( !is_array( $nodesParams ) || !count( $nodesParams ) )
        {
            eZDebug::writeWarning( __METHOD__.': Nodes parameter must be an array with at least one key.' );
            return null;
        }

        if( $listParams === null )
        {
            $listParams = array(
                             'SortBy'                   => false,
                             'Offset'                   => false,
                             'Limit'                    => false,
                             'SortBy'                   => false,
                             'GroupBy'                  => false );
        }

        $offset           = ( isset( $listParams['Offset'] ) && is_numeric( $listParams['Offset'] ) ) ? $listParams['Offset']             : false;
        $limit            = ( isset( $listParams['Limit']  ) && is_numeric( $listParams['Limit']  ) ) ? $listParams['Limit']              : false;
        $groupBy          = ( isset( $listParams['GroupBy']                                       ) ) ? $listParams['GroupBy']            : false;
        if ( !isset( $listParams['SortBy'] ) )
        {
            $listParams['SortBy'] = false;
        }
        $sortBy = $listParams['SortBy'];

        $queryNodes = '';

        foreach( $nodesParams as $nodeParams )
        {
            $nodeID = $nodeParams['ParentNodeID'];

            if ( !is_numeric( $nodeID ) && !is_array( $nodeID ) )
            {
                eZDebug::writeWarning( __METHOD__.': Nodes parameter must be numeric or an array with numeric values.' );
                $retValue = null;
                return $retValue;
            }

            if ( $nodeParams === null )
            {
                $nodeParams = array(
                                 'Depth'                    => false,
                                 //'OnlyTranslated'           => false,
                                 'Language'                 => false,
                                 'AttributeFilter'          => false,
                                 'ExtendedAttributeFilter'  => false,
                                 'ClassFilterType'          => false,
                                 'ClassFilterArray'         => false );
            }

            //$onlyTranslated   = ( isset( $nodeParams['OnlyTranslated']    ) )                       ? $nodeParams['OnlyTranslated']     : false;
            $language         = ( isset( $nodeParams['Language']          ) )                             ? $nodeParams['Language']           : false;
            $depth            = ( isset( $nodeParams['Depth']  ) && is_numeric( $nodeParams['Depth']  ) ) ? $nodeParams['Depth']              : false;
            $depthOperator    = ( isset( $nodeParams['DepthOperator']     ) )                         ? $nodeParams['DepthOperator']      : false;
            $asObject         = ( isset( $nodeParams['AsObject']          ) )                         ? $nodeParams['AsObject']           : true;
            $mainNodeOnly     = ( isset( $nodeParams['MainNodeOnly']      ) )                         ? $nodeParams['MainNodeOnly']       : false;
            $ignoreVisibility = ( isset( $nodeParams['IgnoreVisibility']  ) )                         ? $nodeParams['IgnoreVisibility']   : false;
            if ( !isset( $nodeParams['ClassFilterType'] ) )
            {
                $nodeParams['ClassFilterType'] = false;
            }

            if ( $language )
            {
                if ( !is_array( $language ) )
                {
                    $language = array( $language );
                }
                eZContentLanguage::setPrioritizedLanguages( $language );
            }

            $sortingInfo             = eZContentObjectTreeNode::createSortingSQLStrings( $sortBy );
            $classCondition          = eZContentObjectTreeNode::createClassFilteringSQLString( $nodeParams['ClassFilterType'], $nodeParams['ClassFilterArray'] );
            $attributeFilter         = eZContentObjectTreeNode::createAttributeFilterSQLStrings( $nodeParams['AttributeFilter'], $sortingInfo );
            $extendedAttributeFilter = eZContentObjectTreeNode::createExtendedAttributeFilterSQLStrings( $nodeParams['ExtendedAttributeFilter'] );
            $mainNodeOnlyCond        = eZContentObjectTreeNode::createMainNodeConditionSQLString( $mainNodeOnly );

            $pathStringCond     = '';
            $notEqParentString  = '';
            // If the node(s) doesn't exist we return null.

            if ( !eZContentObjectTreeNode::createPathConditionAndNotEqParentSQLStrings( $pathStringCond, $notEqParentString, $nodeID, $depth, $depthOperator ) )
            {
                $retValue = null;
                return $retValue;
            }

            $useVersionName     = true;
            $versionNameTables  = eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
            $versionNameTargets = eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
            $versionNameJoins   = eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName, false );

            $languageFilter = ' AND ' . eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

            if ( $language )
            {
                eZContentLanguage::clearPrioritizedLanguages();
            }

            $limitation = ( isset( $nodeParams['Limitation']  ) && is_array( $nodeParams['Limitation']  ) ) ? $nodeParams['Limitation']: false;
            $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
            $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );

            // Determine whether we should show invisible nodes.
            $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( !$ignoreVisibility );

            $queryNodes .= " (
                          $pathStringCond
                          $extendedAttributeFilter[joins]
                          $sortingInfo[attributeWhereSQL]
                          $attributeFilter[where]
                          ezcontentclass.version=0 AND
                          $notEqParentString
                          ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id AND
                          $mainNodeOnlyCond
                          $classCondition
                          $versionNameJoins
                          $showInvisibleNodesCond
                          $sqlPermissionChecking[where]
                          $languageFilter
                      )
                      OR";
        }

        $groupBySelectText  = '';
        $groupBySQL         = $extendedAttributeFilter['group_by'];
        if ( !$groupBySQL )
        {
            eZContentObjectTreeNode::createGroupBySQLStrings( $groupBySelectText, $groupBySQL, $groupBy );
        }
        else if ( $groupBy )
        {
            eZDebug::writeError( "Cannot use group_by parameter together with extended attribute filter which sets group_by!", __METHOD__ );
        }

        $query = "SELECT DISTINCT
                       ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.serialized_name_list as class_serialized_name_list,
                       ezcontentclass.identifier as class_identifier,
                       ezcontentclass.is_container as is_container
                       $groupBySelectText
                       $versionNameTargets
                       $sortingInfo[attributeTargetSQL]
                       , ".$nodeParams['ResultID']." AS resultid
                   FROM
                      ezcontentobject_tree,
                      ezcontentobject,ezcontentclass
                      $versionNameTables
                      $sortingInfo[attributeFromSQL]
                      $attributeFilter[from]
                      $extendedAttributeFilter[tables]
                      $sqlPermissionChecking[from]
                   WHERE
                      ".substr($queryNodes, 0, -2)."
                $groupBySQL";

        if ( $sortingInfo['sortingFields'] )
        {
            $query .= " ORDER BY $sortingInfo[sortingFields]";
        }

        $db = eZDB::instance();

        $server = count( $sqlPermissionChecking['temp_tables'] ) > 0 ? eZDBInterface::SERVER_SLAVE : false;

        if ( !$offset && !$limit )
        {
            $nodeListArray = $db->arrayQuery( $query, array(), $server );
        }
        else
        {
            $nodeListArray = $db->arrayQuery( $query, array( 'offset' => $offset,
                                                              'limit'  => $limit ),
                                                      $server );
        }

        if ( $asObject )
        {
            $retNodeList = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
        }
        else
        {
            $retNodeList = $nodeListArray;
        }

        // cleanup temp tables
        $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );

        return $retNodeList;
    }

    static function subTreeGroupByDateField( $field, $type )
    {
        $divisor = 0;
        switch ( $type )
        {
            case 'year':
            {
                $divisor = 60*60*24*365;
            } break;
            case 'week':
            {
                $divisor = 60*60*24*7;
            } break;
            case 'day':
            {
                $divisor = 60*60*24;
            } break;
            case 'hour':
            {
                $divisor = 60*60;
            } break;
            case 'minute':
            {
                $divisor = 60;
            } break;
            case 'second':
            {
                $divisor = 0;
            } break;
            default:
            {
                eZDebug::writeError( "Unknown field type $type", __METHOD__ );
            }
        }
        if ( $divisor > 0 )
            $text = "( $field / $divisor ) AS groupbyfield";
        else
            $text = "$field AS groupbyfield";
        return array( 'select' => $text,
                      'group_field' => "( $field / $divisor )" );
    }


    /*!
     \sa subTreeCount
    */
    static function subTreeCountByNodeID( $params = array(), $nodeID )
    {
        if ( !is_numeric( $nodeID ) and !is_array( $nodeID ) )
        {
            return null;
        }

        $language = ( isset( $params['Language'] ) ) ? $params['Language'] : false;

        if ( $language )
        {
            if ( !is_array( $language ) )
            {
                $language = array( $language );
            }
            eZContentLanguage::setPrioritizedLanguages( $language );
        }

        $depth         = isset( $params['Depth'] ) && is_numeric( $params['Depth'] ) ? $params['Depth']              : false;
        $depthOperator = isset( $params['DepthOperator'] )                           ? $params['DepthOperator']      : false;

        $pathStringCond     = '';
        $notEqParentString  = '';
        // If the node(s) doesn't exist we return null.
        if ( !eZContentObjectTreeNode::createPathConditionAndNotEqParentSQLStrings( $pathStringCond, $notEqParentString, $nodeID, $depth, $depthOperator ) )
        {
            return null;
        }

        $db = eZDB::instance();
        $ini = eZINI::instance();

        // Check for class filtering
        $classCondition = '';

        if ( isset( $params['ClassFilterType'] ) and isset( $params['ClassFilterArray'] ) and
             ( $params['ClassFilterType'] == 'include' or $params['ClassFilterType'] == 'exclude' )
             and count( $params['ClassFilterArray'] ) > 0 )
        {
            $classCondition = '  ';
            $i = 0;
            $classCount = count( $params['ClassFilterArray'] );
            $classIDArray = array();
            foreach ( $params['ClassFilterArray'] as $classID )
            {
                $originalClassID = $classID;
                // Check if classes are recerenced by identifier
                if ( is_string( $classID ) && !is_numeric( $classID ) )
                {
                    $classID = eZContentClass::classIDByIdentifier( $classID );
                }
                if ( is_numeric( $classID ) )
                {
                    $classIDArray[] = $classID;
                }
                else
                {
                    eZDebugSetting::writeWarning( 'kernel-content-class', "Invalid class identifier in subTree() classfilterarray, classID : " . $originalClassID );
                }
            }
            if ( count( $classIDArray ) > 0  )
            {
                $classCondition .= " ezcontentobject.contentclass_id ";
                if ( $params['ClassFilterType'] == 'include' )
                    $classCondition .= " IN ";
                else
                    $classCondition .= " NOT IN ";

                $classIDString =  implode( ', ', $classIDArray );
                $classCondition .= ' ( ' . $classIDString . ' ) AND';
            }
        }


        // Main node check
        $mainNodeOnlyCond = '';
        if ( isset( $params['MainNodeOnly'] ) && $params['MainNodeOnly'] === true )
        {
            $mainNodeOnlyCond = 'ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id AND';
        }

        $attributeFilterParam = isset( $params['AttributeFilter'] ) ? $params['AttributeFilter'] : false;
        $attributeFilter = eZContentObjectTreeNode::createAttributeFilterSQLStrings( $attributeFilterParam );
        if ( $attributeFilter === false )
        {
            return null;
        }

        //$onlyTranslated   = ( isset( $params['OnlyTranslated'] ) ) ? $params['OnlyTranslated']     : false;

        $useVersionName     = true;

        $versionNameTables  = eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
        $versionNameTargets = eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
        $versionNameJoins   = eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName, false );

        $languageFilter = ' AND '.eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

        if ( $language )
        {
            eZContentLanguage::clearPrioritizedLanguages();
        }
        $objectNameFilter = ( isset( $params['ObjectNameFilter']  ) ) ? $params['ObjectNameFilter']   : false;
        $objectNameFilterSQL = eZContentObjectTreeNode::createObjectNameFilterConditionSQLString( $objectNameFilter );

        $extendedAttributeFilter = eZContentObjectTreeNode::createExtendedAttributeFilterSQLStrings( $params['ExtendedAttributeFilter'] );

        // Determine whether we should show invisible nodes.
        $ignoreVisibility = isset( $params['IgnoreVisibility'] ) ? $params['IgnoreVisibility'] : false;
        $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( !$ignoreVisibility );

        $limitation = ( isset( $params['Limitation']  ) && is_array( $params['Limitation']  ) ) ? $params['Limitation']: false;
        $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
        $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );

        $query = "SELECT
                        count( DISTINCT ezcontentobject_tree.node_id ) as count
                  FROM
                       ezcontentobject_tree,
                       ezcontentobject,ezcontentclass
                       $versionNameTables
                       $attributeFilter[from]
                       $extendedAttributeFilter[tables]
                       $sqlPermissionChecking[from]
                  WHERE $pathStringCond
                        $extendedAttributeFilter[joins]
                        $mainNodeOnlyCond
                        $classCondition
                        $attributeFilter[where]
                        ezcontentclass.version=0 AND
                        $notEqParentString
                        ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                        ezcontentclass.id = ezcontentobject.contentclass_id AND
                        $versionNameJoins
                        $showInvisibleNodesCond
                        $sqlPermissionChecking[where]
                        $objectNameFilterSQL
                        $languageFilter ";

        $server = count( $sqlPermissionChecking['temp_tables'] ) > 0 ? eZDBInterface::SERVER_SLAVE : false;

        $nodeListArray = $db->arrayQuery( $query, array(), $server );

        // cleanup temp tables
        $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );

        return $nodeListArray[0]['count'];
    }

    /*!
     Count number of subnodes

     \param params array
    */
    function subTreeCount( $params = array() )
    {
        return eZContentObjectTreeNode::subTreeCountByNodeID( $params, $this->attribute( 'node_id' ) );
    }

    /*!
      \return The date/time list when object were published
    */
    static function calendar( $params = false, $nodeID = 0 )
    {
        if ( !is_numeric( $nodeID ) and !is_array( $nodeID ) )
        {
            return array();
        }

        if ( $params === false )
        {
            $params = array( 'Depth'                    => false,
                             'Offset'                   => false,
                             'Limit'                    => false,
                             'AttributeFilter'          => false,
                             'ExtendedAttributeFilter'  => false,
                             'ClassFilterType'          => false,
                             'ClassFilterArray'         => false,
                             'GroupBy'                  => false );
        }

        $offset           = ( isset( $params['Offset'] ) && is_numeric( $params['Offset'] ) ) ? $params['Offset']             : false;
        $limit            = ( isset( $params['Limit']  ) && is_numeric( $params['Limit']  ) ) ? $params['Limit']              : false;
        $depth            = ( isset( $params['Depth']  ) && is_numeric( $params['Depth']  ) ) ? $params['Depth']              : false;
        $depthOperator    = ( isset( $params['DepthOperator']     ) )                         ? $params['DepthOperator']      : false;
        $groupBy          = ( isset( $params['GroupBy']           ) )                         ? $params['GroupBy']            : false;
        $mainNodeOnly     = ( isset( $params['MainNodeOnly']      ) )                         ? $params['MainNodeOnly']       : false;
        $ignoreVisibility = ( isset( $params['IgnoreVisibility']  ) )                         ? $params['IgnoreVisibility']   : false;
        if ( !isset( $params['ClassFilterType'] ) )
            $params['ClassFilterType'] = false;

        $classCondition          = eZContentObjectTreeNode::createClassFilteringSQLString( $params['ClassFilterType'], $params['ClassFilterArray'] );
        $attributeFilter         = eZContentObjectTreeNode::createAttributeFilterSQLStrings( $params['AttributeFilter'], $sortingInfo );
        $extendedAttributeFilter = eZContentObjectTreeNode::createExtendedAttributeFilterSQLStrings( $params['ExtendedAttributeFilter'] );
        $mainNodeOnlyCond        = eZContentObjectTreeNode::createMainNodeConditionSQLString( $mainNodeOnly );

        $pathStringCond     = '';
        $notEqParentString  = '';
        eZContentObjectTreeNode::createPathConditionAndNotEqParentSQLStrings( $pathStringCond, $notEqParentString, $nodeID, $depth, $depthOperator );

        $groupBySelectText  = '';
        $groupBySQL         = $extendedAttributeFilter['group_by'];
        if ( !$groupBySQL )
        {
            eZContentObjectTreeNode::createGroupBySQLStrings( $groupBySelectText, $groupBySQL, $groupBy );
        }
        else if ( $groupBy )
        {
            eZDebug::writeError( "Cannot use group_by parameter together with extended attribute filter which sets group_by!", __METHOD__ );
        }

        $useVersionName     = true;
        $versionNameTables  = eZContentObjectTreeNode::createVersionNameTablesSQLString( $useVersionName );
        $versionNameJoins   = eZContentObjectTreeNode::createVersionNameJoinsSQLString( $useVersionName, false );

        $limitation = ( isset( $params['Limitation']  ) && is_array( $params['Limitation']  ) ) ? $params['Limitation']: false;
        $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
        $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );

        // Determine whether we should show invisible nodes.
        $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( !$ignoreVisibility );

        $query = "SELECT DISTINCT
                         ezcontentobject.published as published
                         $groupBySelectText
                   FROM
                      ezcontentobject_tree,
                      ezcontentobject,ezcontentclass
                      $versionNameTables
                      $attributeFilter[from]
                      $extendedAttributeFilter[tables]
                      $sqlPermissionChecking[from]
                   WHERE
                      $pathStringCond
                      $extendedAttributeFilter[joins]
                      $attributeFilter[where]
                      ezcontentclass.version=0
                      AND
                      $notEqParentString
                      $mainNodeOnlyCond
                      ezcontentobject_tree.contentobject_id = ezcontentobject.id AND
                      ezcontentclass.id = ezcontentobject.contentclass_id AND
                      $classCondition
                      $versionNameJoins
                      $showInvisibleNodesCond
                      $sqlPermissionChecking[where]
                $groupBySQL";


        $db = eZDB::instance();

        $server = count( $sqlPermissionChecking['temp_tables'] ) > 0 ? eZDBInterface::SERVER_SLAVE : false;

        if ( !$offset && !$limit )
        {
            $nodeListArray = $db->arrayQuery( $query, array(), $server );
        }
        else
        {
            $nodeListArray = $db->arrayQuery( $query, array( 'offset' => $offset,
                                                              'limit'  => $limit ),
                                                      $server );
        }

        // cleanup temp tables
        $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );

        return $nodeListArray;
    }
    /*!
     \return the children(s) of the current node as an array of eZContentObjectTreeNode objects
    */
    function childrenByName( $name )
    {
        $nodeID = $this->attribute( 'node_id' );

        $fromNode = $nodeID ;

        $nodePath = $this->attribute( 'path_string' );
        $nodeDepth = $this->attribute( 'depth' );

        $childrensPath = $nodePath ;

        $db = eZDB::instance();
        $pathString = " path_string like '$childrensPath%' and ";
        $depthCond = '';
        $nodeDepth = $this->Depth + 1;
        $depthCond = ' depth <= ' . $nodeDepth . ' and ';

        $ini = eZINI::instance();
        $db = eZDB::instance();

        $name = $db->escapeString( $name );
        $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.serialized_name_list as class_serialized_name_list,
                             ezcontentclass.identifier as class_identifier,
                             ezcontentclass.is_container as is_container
                      FROM
                            ezcontentobject_tree,
                            ezcontentobject,ezcontentclass
                      WHERE $pathString
                            $depthCond
                            ezcontentobject.name = '$name' AND
                            ezcontentclass.version=0 AND
                            node_id != $fromNode AND
                            ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                            ezcontentclass.id = ezcontentobject.contentclass_id";

        $nodeListArray = $db->arrayQuery( $query );

        return eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
    }

    /*!
     Returns the first level children in sorted order.
    */
    function children()
    {
        return $this->subTree( array( 'Depth' => 1,
                                      'DepthOperator' => 'eq',
                                      'SortBy' => $this->sortArray() ) );
    }

    /*!
     Returns the number of children for the current node.
     \params $checkPolicies If \c true it will only include nodes which can be read using the current policies,
                            if \c false all nodes are included in count.
    */
    function childrenCount( $checkPolicies = true )
    {
        $params = array( 'Depth' => 1,
                         'DepthOperator' => 'eq' );
        if ( !$checkPolicies )
            $params['Limitation'] = array();
        return $this->subTreeCount( $params );
    }

    /*!
     Get amount views of content node.
    */
    function viewCount()
    {
        $count = eZViewCounter::fetch( $this->attribute( 'node_id' ), false );
        return (int) $count['count'];
    }

    /*!
     \return the sort field name for the numeric sort field ID \a $sortFieldID.
             Gives a warning if the ID is unknown and returns \c 'path'.
    */
    static function sortFieldName( $sortFieldID )
    {
        switch ( $sortFieldID )
        {
            default:
                eZDebug::writeWarning( 'Unknown sort field ID: ' . $sortFieldID, __METHOD__ );
            case self::SORT_FIELD_PATH:
                return 'path';
            case self::SORT_FIELD_PUBLISHED:
                return 'published';
            case self::SORT_FIELD_MODIFIED:
                return 'modified';
            case self::SORT_FIELD_SECTION:
                return 'section';
            case self::SORT_FIELD_DEPTH:
                return 'depth';
            case self::SORT_FIELD_CLASS_IDENTIFIER:
                return 'class_identifier';
            case self::SORT_FIELD_CLASS_NAME:
                return 'class_name';
            case self::SORT_FIELD_PRIORITY:
                return 'priority';
            case self::SORT_FIELD_NAME:
                return 'name';
            case self::SORT_FIELD_MODIFIED_SUBNODE:
                return 'modified_subnode';
            case self::SORT_FIELD_NODE_ID:
                return 'node_id';
            case self::SORT_FIELD_CONTENTOBJECT_ID:
                return 'contentobject_id';
        }
    }

    /*!
     \return the numeric sort field ID for the sort field name \a $sortFieldName.
             Gives a warning if the name is unknown and returns self::SORT_FIELD_PATH.
    */
    static function sortFieldID( $sortFieldName )
    {
        switch ( $sortFieldName )
        {
            default:
                eZDebug::writeWarning( 'Unknown sort field name: ' . $sortFieldName, __METHOD__ );
            case 'path':
                return self::SORT_FIELD_PATH;
            case 'published':
                return self::SORT_FIELD_PUBLISHED;
            case 'modified':
                return self::SORT_FIELD_MODIFIED;
            case 'section':
                return self::SORT_FIELD_SECTION;
            case 'depth':
                return self::SORT_FIELD_DEPTH;
            case 'class_identifier':
                return self::SORT_FIELD_CLASS_IDENTIFIER;
            case 'class_name':
                return self::SORT_FIELD_CLASS_NAME;
            case 'priority':
                return self::SORT_FIELD_PRIORITY;
            case 'name':
                return self::SORT_FIELD_NAME;
            case 'modified_subnode':
                return self::SORT_FIELD_MODIFIED_SUBNODE;
            case 'node_id':
                return self::SORT_FIELD_NODE_ID;
            case 'contentobject_id':
                return self::SORT_FIELD_CONTENTOBJECT_ID;
        }
    }

    /*!
     \return an array which defines the sorting method for this node.
     The array will contain one element which is an array with sort field
     and sort order.
    */
    function sortArray()
    {
        return eZContentObjectTreeNode::sortArrayBySortFieldAndSortOrder( $this->attribute( 'sort_field' ),
                                                                          $this->attribute( 'sort_order' ) );
    }

    /*!
     \static
     \return an array which defines the sorting method for this node.
     The array will contain one element which is an array with sort field
     and sort order.
    */
    static function sortArrayBySortFieldAndSortOrder( $sortField, $sortOrder )
    {
        return array( array( eZContentObjectTreeNode::sortFieldName( $sortField ),
                              $sortOrder ) );
    }

    /*!
     Will assign a section to the current node and all child objects.
     Only main node assignments will be updated.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function assignSectionToSubTree( $nodeID, $sectionID, $oldSectionID = false )
    {
        $db = eZDB::instance();

        $node = eZContentObjectTreeNode::fetch( $nodeID );
        $nodePath =  $node->attribute( 'path_string' );

        $sectionID =(int) $sectionID;

        $pathString = " path_string like '$nodePath%' AND ";

        // fetch the object id's which needs to be updated
        $objectIDArray = $db->arrayQuery( "SELECT
                                                   ezcontentobject.id
                                            FROM
                                                   ezcontentobject_tree, ezcontentobject
                                            WHERE
                                                  $pathString
                                                  ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                                                  ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id" );
        if ( count( $objectIDArray ) == 0 )
            return;

        // Who assigns which section at which node should be logged.
        $section = eZSection::fetch( $sectionID );
        $object = $node->object();
        eZAudit::writeAudit( 'section-assign', array( 'Section ID' => $sectionID, 'Section name' => $section->attribute( 'name' ),
                                                      'Node ID' => $nodeID,
                                                      'Content object ID' => $object->attribute( 'id' ),
                                                      'Content object name' => $object->attribute( 'name' ),
                                                      'Comment' => 'Assigned a section to the current node and all child objects: eZContentObjectTreeNode::assignSectionToSubTree()' ) );

        $objectSimpleIDArray = array();
        foreach ( $objectIDArray as $objectID )
        {
            $objectSimpleIDArray[] = $objectID['id'];
        }

        $filterPart = '';
        if ( $oldSectionID !== false )
        {
            $oldSectionID =(int) $oldSectionID;
            $filterPart = " section_id = '$oldSectionID' and ";
        }

        $db->begin();
        foreach ( array_chunk( $objectSimpleIDArray, 100 ) as $pagedObjectIDs )
        {
            $db->query( "UPDATE ezcontentobject SET section_id='$sectionID' WHERE $filterPart " . $db->generateSQLINStatement( $pagedObjectIDs, 'id', false, true, 'int' ) );
            $db->query( "UPDATE ezsearch_object_word_link SET section_id='$sectionID' WHERE $filterPart " . $db->generateSQLINStatement( $pagedObjectIDs, 'contentobject_id', false, true, 'int' ) );
        }
        $db->commit();

        // clear caches for updated objects
        eZContentObject::clearCache( $objectSimpleIDArray );
    }

    /*!
     \static
     Updates the main node selection for the content object \a $objectID.

     \param $mainNodeID The ID of the node that should be that main node
     \param $objectID The ID of the object that all nodes belong to
     \param $version The version of the object to update node assignments, use \c false for currently published version.
     \param $parentMainNodeID The ID of the parent node of the new main placement

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function updateMainNodeID( $mainNodeID, $objectID, $version = false, $parentMainNodeID, $updateSection = true )
    {
        $mainNodeID = (int)$mainNodeID;
        $parentMainNodeID = (int)$parentMainNodeID;
        $objectID = (int)$objectID;
        $version = (int)$version;

        $db = eZDB::instance();
        $db->begin();
        $db->query( "UPDATE ezcontentobject_tree SET main_node_id=$mainNodeID WHERE contentobject_id=$objectID" );
        if ( !$version )
        {
            $rows = $db->arrayQuery( "SELECT current_version FROM ezcontentobject WHERE id=$objectID" );
            $version = $rows[0]['current_version'];
        }
        $db->query( "UPDATE eznode_assignment SET is_main=1 WHERE contentobject_id=$objectID AND contentobject_version=$version AND parent_node=$parentMainNodeID" );
        $db->query( "UPDATE eznode_assignment SET is_main=0 WHERE contentobject_id=$objectID AND contentobject_version=$version AND parent_node!=$parentMainNodeID" );

        $contentObject = eZContentObject::fetch( $objectID );
        $parentContentObject = eZContentObject::fetchByNodeID( $parentMainNodeID );
        if ( $updateSection && $contentObject->attribute( 'section_id' ) != $parentContentObject->attribute( 'section_id' ) )
        {
            $newSectionID = $parentContentObject->attribute( 'section_id' );
            eZContentObjectTreeNode::assignSectionToSubTree( $mainNodeID, $newSectionID );
        }

        $db->commit();

    }

    function fetchByCRC( $pathStr )
    {
        eZDebug::writeWarning( "Obsolete: use eZURLAlias instead", __METHOD__ );
        return null;
    }

    static function fetchByContentObjectID( $contentObjectID, $asObject = true, $contentObjectVersion = false )
    {
        $conds = array( 'contentobject_id' => $contentObjectID );
        if ( $contentObjectVersion !== false )
        {
            $conds['contentobject_version'] = $contentObjectVersion;
        }
        return eZPersistentObject::fetchObjectList( eZContentObjectTreeNode::definition(),
                                                    null,
                                                    $conds,
                                                    null,
                                                    null,
                                                    $asObject );
    }

    static function fetchByRemoteID( $remoteID, $asObject = true )
    {
        return eZContentObjectTreeNode::fetch( false, false, $asObject, array( "remote_id" => $remoteID ) );
    }

    static function fetchByPath( $pathString, $asObject = true )
    {
        return eZContentObjectTreeNode::fetch( false, false, $asObject, array( "path_string" => $pathString ) );
    }

    static function fetchByURLPath( $pathString, $asObject = true )
    {
        if ( $pathString == "" )
        {
            eZDebug::writeWarning( 'Can not fetch, given URLPath is empty', __METHOD__ );
            return null;
        }

        return eZContentObjectTreeNode::fetch( false, false, $asObject, array( "path_identification_string" => $pathString ) );
    }

    /**
     * Fetches path_identification_string for a list of nodes
     *
     * @param array(int) $nodeList
     *
     * @return array Associative array
     */
    static function fetchAliasesFromNodeList( $nodeList )
    {
        if ( !is_array( $nodeList ) || count( $nodeList ) < 1 )
            return array();

        $db = eZDB::instance();
        $where = $db->generateSQLINStatement( $nodeList, 'node_id', false, false, 'int' );
        $query = "SELECT node_id, path_identification_string FROM ezcontentobject_tree WHERE $where";
        $pathListArray = $db->arrayQuery( $query );
        return $pathListArray;
    }

    static function findMainNode( $objectID, $asObject = false )
    {
        $objectID = (int)$objectID;
        $query = "SELECT node_id
                  FROM ezcontentobject_tree
                  WHERE contentobject_id=$objectID AND
                  main_node_id = node_id";
        $db = eZDB::instance();
        $nodeListArray = $db->arrayQuery( $query );
        if ( count( $nodeListArray ) == 1 )
        {
            if ( $asObject )
            {
                 return eZContentObjectTreeNode::fetch( $nodeListArray[0]['node_id'] );
            }
            else
            {
                return $nodeListArray[0]['node_id'];
            }

        }
        else if ( count( $nodeListArray ) > 1 )
        {
            eZDebug::writeError( $nodeListArray , "There are more then one main_node for objectID: $objectID" );
        }

        return null;
    }

    /**
     * Fetches the main nodes for an array of object id's
     * @param array(int) $objectIDArray an array of object IDs
     * @param bool $asObject
     *        Wether to return the result as an array of eZContentObjectTreeNode
     *        (true) or as an array of associative arrays (false)
     * @return array(array|eZContentObjectTreeNode)
     */
    static function findMainNodeArray( $objectIDArray, $asObject = true )
    {
        if ( count( $objectIDArray ) )
        {
            $db = eZDB::instance();
            $objectIDINSQL = $db->generateSQLINStatement( $objectIDArray, 'ezcontentobject_tree.contentobject_id', false, false, 'int' );
            $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.serialized_name_list as class_serialized_name_list,
                           ezcontentclass.identifier as class_identifier,
                           ezcontentclass.is_container as is_container
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                    WHERE $objectIDINSQL AND
                          ezcontentobject_tree.main_node_id = ezcontentobject_tree.node_id AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id";

            $nodeListArray = $db->arrayQuery( $query );
            if ( $asObject )
            {
                $retNodeArray = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
                return $retNodeArray;
            }
            else
            {
                return $nodeListArray;
            }
        }
        return null;
    }


    /**
     * Fetches a node by ID
     *
     * @param int|array $nodeID Either a node ID or array of node IDs
     * @param string $lang language code to fetch the node in. If not provided, the prioritized language list is used
     * @param bool $asObject True to fetch the node as an eZContentObjectTreeNode, false to fetch its attributes as an array
     * @param array $conditions An associative array (field => value) of fetch conditions. Will be applied as is to the SQL query
     *
     * @return eZContentObjectTreeNode
    */
    static function fetch( $nodeID = false, $lang = false, $asObject = true, $conditions = false )
    {
        $returnValue = null;
        $db = eZDB::instance();
        if ( ( is_numeric( $nodeID ) && $nodeID == 1 ) ||
             ( is_array( $nodeID ) && count( $nodeID ) === 1 && $nodeID[0] == 1 ) )
        {
            $query = "SELECT *
                FROM ezcontentobject_tree
                WHERE node_id = 1";
        }
        else
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';
            $versionNameJoins = " and  ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version and ";
            if ( $lang )
            {
                $lang = $db->escapeString( $lang );
                $versionNameJoins .= " ezcontentobject_name.content_translation = '$lang' ";
            }
            else
            {
                $versionNameJoins .= eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject' );
            }

            $languageFilter = ' AND '.eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

            $sqlCondition = '';

            if ( $nodeID !== false )
            {
                if ( is_array( $nodeID ) )
                {
                    if( count( $nodeID ) === 1 )
                    {
                        $sqlCondition = 'node_id = ' . (int) $nodeID[0] . ' AND ';
                    }
                    else
                    {
                        $sqlCondition = $db->generateSQLINStatement( $nodeID, 'node_id', false, true, 'int' ) . ' AND ';
                    }
                }
                else
                {
                    $sqlCondition = 'node_id = ' . (int) $nodeID . ' AND ';
                }
            }

            if ( is_array( $conditions ) )
            {
                foreach( $conditions as $key => $condition )
                {
                    if ( is_string( $condition ) )
                    {
                        $condition = $db->escapeString( $condition );
                        $condition = "'$condition'";
                    }

                    $sqlCondition .= "ezcontentobject_tree." . $db->escapeString( $key ) . "=$condition AND ";
                }
            }

            if ( $sqlCondition == '' )
            {
                eZDebug::writeWarning( 'Cannot fetch node, emtpy ID or no conditions given', __METHOD__ );
                return $returnValue;
            }

            $query="SELECT ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.serialized_name_list as class_serialized_name_list,
                       ezcontentclass.identifier as class_identifier,
                       ezcontentclass.is_container as is_container
                       $versionNameTargets
                FROM ezcontentobject_tree,
                     ezcontentobject,
                     ezcontentclass
                     $versionNameTables
                WHERE $sqlCondition
                      ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                      ezcontentclass.version=0  AND
                      ezcontentclass.id = ezcontentobject.contentclass_id
                      $languageFilter
                      $versionNameJoins";
        }
        $nodeListArray = $db->arrayQuery( $query );

        if ( count( $nodeListArray ) > 0 )
        {
            if ( $asObject )
            {
                $returnValue = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
                if ( count( $returnValue ) === 1 )
                    $returnValue = $returnValue[0];
            }
            else
            {
                if ( count( $nodeListArray ) === 1 )
                    $returnValue = $nodeListArray[0];
                else
                    $returnValue = $nodeListArray;
            }
        }

        return $returnValue;
    }

    /*!
     \static
     Finds the node for the object \a $contentObjectID which placed as child of node \a $parentNodeID.
     \return An eZContentObjectTreeNode object or \c null if no node was found.
    */
    static function fetchNode( $contentObjectID, $parentNodeID )
    {
        $returnValue = null;
        $ini = eZINI::instance();
        $db = eZDB::instance();
        $contentObjectID =(int) $contentObjectID;
        $parentNodeID =(int) $parentNodeID;
        $query = "SELECT ezcontentobject_tree.*,
                         ezcontentclass.serialized_name_list as class_serialized_name_list,
                         ezcontentclass.identifier as class_identifier,
                         ezcontentclass.is_container as is_container
                  FROM ezcontentobject_tree,
                       ezcontentobject,
                       ezcontentclass
                  WHERE ezcontentobject_tree.contentobject_id = '$contentObjectID' AND
                        ezcontentobject.id = '$contentObjectID' AND
                        ezcontentobject_tree.parent_node_id = '$parentNodeID' AND
                        ezcontentclass.version=0 AND
                        ezcontentclass.id = ezcontentobject.contentclass_id AND ".
                        eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

        $nodeListArray = $db->arrayQuery( $query );
        if ( count( $nodeListArray ) == 1 )
        {
            $retNodeArray = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray, false );
            $returnValue = $retNodeArray[0];
        }
        return $returnValue;
    }

    function fetchParent()
    {
        return $this->fetch( $this->attribute( 'parent_node_id' ) );
    }

    function pathArray()
    {
        $pathString = $this->attribute( 'path_string' );
        $pathItems = explode( '/', $pathString );
        $pathArray = array();
        foreach ( $pathItems as $pathItem )
        {
            if ( $pathItem != '' )
                $pathArray[] = (int) $pathItem;
        }
        return $pathArray;
    }


    function fetchPath()
    {
        $nodePath = $this->attribute( 'path_string' );

        return eZContentObjectTreeNode::fetchNodesByPathString( $nodePath, false, true );
    }

    /*!
     \static
     \return An array with content node objects that is present in the node path \a $nodePath.
     \param $withLastNode If \c true the last node in the path is included in the list.
                          The last node is the node which the path was fetched from.
     \param $asObjects If \c true then return PHP objects, if not return raw row data.
     \param $limit maximum number of nodes in the path to use, starting from last node
    */
    static function fetchNodesByPathString( $nodePath, $withLastNode = false, $asObjects = true, $limit = false )
    {
        $nodesListArray = array();
        $pathString = eZContentObjectTreeNode::createNodesConditionSQLStringFromPath( $nodePath, $withLastNode, $limit );

        if ( $pathString  )
        {
            $useVersionName     = true;
            $versionNameTables  = eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
            $versionNameTargets = eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
            $versionNameJoins   = eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName );

            $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.serialized_name_list as class_serialized_name_list,
                             ezcontentclass.identifier as class_identifier,
                             ezcontentclass.is_container as is_container
                             $versionNameTargets
                      FROM ezcontentobject_tree,
                           ezcontentobject,
                           ezcontentclass
                           $versionNameTables
                      WHERE $pathString
                            ezcontentobject_tree.contentobject_id=ezcontentobject.id  AND
                            ezcontentclass.version=0 AND
                            ezcontentclass.id = ezcontentobject.contentclass_id
                            $versionNameJoins
                      ORDER BY path_string";

            $db = eZDB::instance();
            $nodesListArray = $db->arrayQuery( $query );
        }

        if ( $asObjects )
        {
            return eZContentObjectTreeNode::makeObjectsArray( $nodesListArray );
        }
        return $nodesListArray;
    }


    /*!
     \static
     Extracts each node that in the path from db and returns an array of class identifiers
     \param $nodePath A string containing the path of the node, it consists of
                      node IDs starting from the root and delimited by / (slash).
     \param $withLastNode If \c true the last node in the path is included in the list.
                          The last node is the node which the path was fetched from.
     \param $limit maximum number of nodes in the path to use, starting from last node
     \return An array with class identifier and node ID.

     Example
     \code
     $list = fetchClassIdentifierListByPathString( '/2/10/', false );
     \endcode
    */
    static function fetchClassIdentifierListByPathString( $nodePath, $withLastNode, $limit = false )
    {
        $itemList = array();
        $nodes = eZContentObjectTreeNode::fetchNodesByPathString( $nodePath, $withLastNode, false, $limit );

        foreach ( $nodes as $node )
        {
            $itemList[]  = array( 'node_id'          => $node['node_id'],
                                  'class_identifier' => $node['class_identifier'] );
        }

        return $itemList;
    }

    /*!
     \deprecated This function should no longer be used, use the eZContentClass::instantiate and eZNodeAssignment::create instead.
    */
    function createObject( $contentClassID, $parentNodeID = 2 )
    {
        $class = eZContentClass::fetch( $contentClassID );
        $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID );
        $parentContentObject = $parentNode->attribute( 'object' );
        $sectionID = $parentContentObject->attribute( 'section_id' );
        $object = $class->instantiate( false, $sectionID );

//        $parentContentObject = $parentNode->attribute( 'contentobject' );

        $node = eZContentObjectTreeNode::addChildTo( $object->attribute( "id" ), $parentNodeID, true );
//        $object->setAttribute( "main_node_id", $node->attribute( 'node_id' ) );
        $node->setAttribute( 'main_node_id', $node->attribute( 'node_id' ) );
        $object->store();
        $node->store();

        return $object;
    }

    /*!
     Add a child for this node to the object tree.
     \param $contentobjectID      The ID of the contentobject the child-node should point to.
     \param $asObject             If true it will return the new child-node as an object, if not it returns the ID.
     \param $contentObjectVersion The version to use on the newly created child-node, if
                                  false it uses the current_version of the specified object.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function addChild( $contentobjectID, $asObject = false, $contentObjectVersion = false )
    {
        return self::addChildTo( $contentobjectID, $this->attribute( 'node_id' ), $asObject, $contentObjectVersion );
    }

    /*!
     Add a child to the object tree.
     \param $contentobjectID      The ID of the contentobject the child-node should point to.
     \param $nodeID               The ID of the parent-node to add child-node to.
     \param $asObject             If true it will return the new child-node as an object, if not it returns the ID.
     \param $contentObjectVersion The version to use on the newly created child-node, if
                                  false it uses the current_version of the specified object.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function addChildTo( $contentobjectID, $nodeID, $asObject = false, $contentObjectVersion = false )
    {
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        $contentObject = eZContentObject::fetch( $contentobjectID );
        if ( !$contentObject )
        {
            return false;
        }

        if ( !$contentObjectVersion )
        {
            $contentObjectVersion = $contentObject->attribute( 'current_version' );
        }

        $db = eZDB::instance();
        $parentMainNodeID = $node->attribute( 'node_id' ); //$parent->attribute( 'main_node_id' );
        $parentPath = $node->attribute( 'path_string' );
        $parentDepth = $node->attribute( 'depth' );
        $isInvinsible = $node->attribute( 'is_invisible' );

        $nodeDepth = $parentDepth + 1 ;

        $insertedNode = eZContentObjectTreeNode::create( $parentMainNodeID, $contentobjectID );

        // set default sorting from content class
        $contentClass = $contentObject->attribute( 'content_class' );
        $insertedNode->setAttribute( 'sort_field', $contentClass->attribute( 'sort_field' ) );
        $insertedNode->setAttribute( 'sort_order', $contentClass->attribute( 'sort_order' ) );

        $insertedNode->setAttribute( 'depth', $nodeDepth );
        $insertedNode->setAttribute( 'path_string', '/TEMPPATH' );

        $insertedNode->setAttribute( 'contentobject_version', $contentObjectVersion );

        // If the parent node is invisible, the new created node should be invisible as well.
        $insertedNode->setAttribute( 'is_invisible', $isInvinsible );

        $db->begin();
        $insertedNode->store();
        $insertedID = $insertedNode->attribute( 'node_id' );
        $newNodePath = $parentPath . $insertedID . '/';
        $insertedNode->setAttribute( 'path_string', $newNodePath );
        $insertedNode->store();
        $db->commit();

        if ( $asObject )
        {
            return $insertedNode;
        }
        else
        {
            return $insertedID;
        }
    }

    /*!
     \return an url alias for the current node. It will generate a unique alias.
    */
    function pathWithNames( $regenerateCurrent = false )
    {
        // Only set name if current node is not the content root
        $ini = eZINI::instance( 'content.ini' );
        $contentRootID = $ini->variable( 'NodeSettings', 'RootNode' );
        if ( $this->attribute( 'node_id' ) != $contentRootID )
        {
            $pathArray = $this->pathArray();
            // Get rid of node with ID 1 (a special node)
            array_shift( $pathArray );
            if ( $regenerateCurrent )
            {
                // Get rid of current node, path element for this will be calculated
                array_pop( $pathArray );
            }
            if ( count( $pathArray ) == 0 )
            {
                $path = '';
            }
            else
            {
                $path = eZURLAliasML::fetchPathByActionList( "eznode", $pathArray, $this->CurrentLanguage );
            }

            // Fallback in case fetchPathByActionList() fails,
            // then we ask for the path from the parent and generate the current
            // entry ourselves.
            if ( $path === null )
            {
                if ( $this->attribute( 'depth' ) == 0 ) // Top node should just return empty string
                {
                    return '';
                }

                eZDebug::writeError( __METHOD__ . "() failed to fetch path of node " . $this->attribute( 'node_id' ) . ", falling back to generated url entries. Run updateniceurls.php to fix the problem." );

                // Return a perma-link when the path lookup failed, this link will always work
                $path = 'content/view/full/' . $this->attribute( 'node_id' );
                return $path;
            }

            if ( $regenerateCurrent )
            {
                $nodeName = $this->attribute( 'name' );
                $nodeName = eZURLAliasML::convertToAlias( $nodeName, 'node_' . $this->attribute( 'node_id' ) );

                if ( $path != '' )
                {
                    $path .= '/' . $nodeName ;
                }
                else
                {
                    $path  = $nodeName ;
                }
            }
        }
        else
        {
            $path = '';
        }

        if ( $regenerateCurrent )
        {
            $path = $this->checkPath( $path );
        }
        return $path;
    }

    /*!
     Check if a node with the same name already exists. If so create a $name + __x value.
    */
    function checkPath( $path )
    {
        $path = eZURLAliasML::cleanURL( $path );
        $elements = explode( "/", $path );
        $element = array_pop( $elements );
        return $this->adjustPathElement( $element );
    }

    /*!
     Checks the path element $element against reserved words and existing elements.
     If the path element is already used, it will append a number and try again.

     The adjusted path element is returned.

     \param $element The desired url element name
     \param $useParentFromNodeObject Use the parent from node object as a base
                                     for checking name collisions. This is needed
                                     when moving nodes, and the url entries are
                                     not updated yet.

     \code
     echo $node->adjustPathElement( 'Content' ); // outputs Content1
     \endcode
     */
    function adjustPathElement( $element, $useParentFromNodeObject = false )
    {
        $nodeID       = (int)$this->attribute( 'node_id' );
        $parentNodeID = (int)$this->attribute( 'parent_node_id' );
        $action       = "eznode:" . $nodeID;

        $elements = eZURLAliasML::fetchByAction( 'eznode', $nodeID );
        if ( count( $elements ) > 0 and !$useParentFromNodeObject )
        {
            $parentElementID = (int)$elements[0]->attribute( 'parent' );
            return eZURLAliasML::findUniqueText( $parentElementID, $element, $action );
        }
        else
        {
            $parentElements = eZURLAliasML::fetchByAction( 'eznode', $parentNodeID );
            if ( count( $parentElements ) > 0 && $parentElements[0]->attribute( 'text' ) != '' )
            {
                // Pick one of the parents and get the ID
                $parentElementID = (int)$parentElements[0]->attribute( 'id' );
                return eZURLAliasML::findUniqueText( $parentElementID, $element, $action );
            }
        }
        return eZURLAliasML::findUniqueText( 0, $element, $action );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     \deprecated Use updateSubTreePath() instead.
     */
    function updateURLAlias()
    {
        eZDebug::writeWarning( __METHOD__ . " is deprecated, use updateSubTreePath() instead" );
        return $this->updateSubTreePath();
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function updateSubTreePath( $updateParent = true, $nodeMove = false )
    {
        $changeCount = 0;

        $nodeID       = $this->attribute( 'node_id' );
        $parentNodeID = $this->attribute( 'parent_node_id' );

        // Avoid recursion due to database inconsistencies
        if ( $nodeID === $parentNodeID )
        {
            eZDebug::writeError( "Parent node ID equals node ID for node: $nodeID. The node cannot be a parent of itself!", __METHOD__ );
            return false;
        }

        // Only set name if current node is not the content root
        $ini = eZINI::instance( 'content.ini' );
        $contentRootID = $ini->variable( 'NodeSettings', 'RootNode' );
        $obj           = $this->object();
        $alwaysMask    = ( $obj->attribute( 'language_mask' ) & 1 );
        $languages     = $obj->allLanguages();
        $nameList      = array();

        $initialLanguageID      = $obj->attribute( 'initial_language_id' );
        $pathIdentificationName = false;
        foreach ( $languages as $language )
        {
            $nodeName = '';
            if ( $nodeID != $contentRootID )
            {
                $objClass = $obj->attribute( 'content_class' );
                $nodeName = $objClass->urlAliasName( $obj, false, $language->attribute( 'locale' ) );
                $nodeName = eZURLAliasFilter::processFilters( $nodeName, $language, $this );
                $nodeName = eZURLAliasML::convertToAlias( $nodeName, 'node_' . $nodeID );
                $nodeName = $this->adjustPathElement( $nodeName, $nodeMove );

                // Compatibility mode:
                // Store name for the 'path_identification_string' column.
                if ( $initialLanguageID == $language->attribute( 'id' ) )
                {
                    $pathIdentificationName = eZURLAliasML::convertToAliasCompat( $nodeName, 'node_' . $nodeID );
                }
            }
            $nameList[] = array( 'text'     => $nodeName,
                                 'language' => $language );
        }

        $parentActionName  = "eznode";
        $parentActionValue = $parentNodeID;

        $parentElementID = false;
        $existingElements = eZURLAliasML::fetchByAction( "eznode", $nodeID );
        $existingElementID = null;
        if ( count( $existingElements ) > 0 )
        {
            $existingElementID = $existingElements[0]->attribute( 'id' );
            $parentElementID = $existingElements[0]->attribute( 'parent' );
        }

        // If we have parent element it means the node is already published
        // and we have to see if it has been moved
        if ( $parentNodeID != 1 and $updateParent )
        {
            $parents = eZURLAliasML::fetchByAction( "eznode", $parentNodeID );
            if ( count( $parents ) == 0 )
            {
                $parentNode = $this->fetchParent();

                if ( !$parentNode )
                {
                    return false;
                }

                $result = $parentNode->updateSubTreePath();
                if ( !$result )
                {
                    return false;
                }
                $parents = eZURLAliasML::fetchByAction( $parentActionName, $parentActionValue );
                if ( count( $parents ) == 0 )
                {
                    return false;
                }
                $oldParentElementID = $parentElementID;
                foreach ( $parents as $paren )
                {
                    $parentElementID = 0;
                    if ( $paren->attribute( 'text' ) != '' )
                    {
                        $parentElementID = (int)$paren->attribute( 'link' );
                        break;
                    }
                }
            }
            else
            {
                $oldParentElementID = $parentElementID;
                $parentElementID = 0;
                foreach ( $parents as $paren )
                {
                    if ( $paren->attribute( 'text' ) != '' )
                    {
                        $parentElementID = (int)$paren->attribute( 'link' );
                        break;
                    }
                }
            }
        }
        else // Parent is ID 1, ie. this node is top-level
        {
        }

        $this->updatePathIdentificationString( $pathIdentificationName );

        $languageID = $obj->attribute( 'initial_language_id' );
        $cleanup    = false;
        foreach ( $nameList as $nameEntry )
        {
            $text     = $nameEntry['text'];
            $language = $nameEntry['language'];
            $result = eZURLAliasML::storePath( $text, 'eznode:' . $nodeID, $language, false, $alwaysMask, $parentElementID, $cleanup );
            if ( $result['status'] === true )
                $changeCount++;
        }
        return $changeCount;
    }

    /*!
     \private
     Updates the path_identification_string field in ezcontentobject_tree by
     fetching the value from the parent and appending $pathIdentificationName.

     \note This stores the current object to the database
     */
    function updatePathIdentificationString( $pathIdentificationName )
    {
        // Update the path_identification_string column for the node
        $pathIdentificationString = '';
        if ( $this->attribute( 'parent_node_id' ) != 1 )
        {
            if ( !isset( $parentNode ) )
                $parentNode = $this->fetchParent();
            // Avoid crashes due to database inconsistencies
            if ( $parentNode instanceof eZContentObjectTreeNode )
            {
                $pathIdentificationString = $parentNode->attribute( 'path_identification_string' );
            }
            else
            {
                eZDebug::writeError( 'Failed to fetch parent node for pathIdentificationName "' . $pathIdentificationName .
                                     '" and parent_node_id ' . $this->attribute( 'parent_node_id' ), __METHOD__ );
            }
        }
        if ( strlen( $pathIdentificationString ) > 0 )
            $pathIdentificationString .= '/' . $pathIdentificationName;
        else
            $pathIdentificationString = $pathIdentificationName;
        if ( $this->attribute( 'path_identification_string' ) != $pathIdentificationString )
        {
            $this->setAttribute( 'path_identification_string', $pathIdentificationString );
            $this->sync();
        }
    }

    /*!
     \static
     \sa removeThis
    */
    static function removeNode( $nodeID = 0 )
    {
        $node = eZContentObjectTreeNode::fetch( $nodeID );
        if ( !is_object( $node ) )
        {
            return;
        }

        return $node->removeThis();
    }

    /*!
      Removes the current node.
      Use ->removeNodeFromTree() if you need to handle main node change + remove object if needed

      \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removeThis()
    {
        $ini = eZINI::instance();

        $object = $this->object();
        $nodeID = $this->attribute( 'node_id' );
        if ( eZAudit::isAuditEnabled() )
        {
            // Set audit params.
            $objectID = $object->attribute( 'id' );
            $objectName = $object->attribute( 'name' );

            eZAudit::writeAudit( 'content-delete', array( 'Node ID' => $nodeID, 'Object ID' => $objectID, 'Content Name' => $objectName,
                                                          'Comment' => 'Removed the current node: eZContentObjectTreeNode::removeNode()' ) );
        }

        $db = eZDB::instance();
        $db->begin();

        $nodePath = $this->attribute( 'path_string' );
        $childrensPath = $nodePath ;

        $pathString = " path_string like '$childrensPath%' ";

        $urlAlias = $this->attribute( 'url_alias' );

        // Remove static cache
        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            $staticCache = new eZStaticCache();
            $staticCache->removeURL( "/" . $urlAlias );
            $staticCache->generateAlwaysUpdatedCache();

            $parent = $this->fetchParent();
        }

        $db->query( "DELETE FROM ezcontentobject_tree
                            WHERE $pathString OR
                            path_string = '$nodePath'" );

        // Re-cache parent node
        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            if ( $parent )
            {
                $staticCache->cacheURL( "/" . $parent->urlAlias() );
            }
        }

        // Clean up URL alias entries
        eZURLAliasML::removeByAction( 'eznode', $nodeID );

        // Clean up content cache
        eZContentCacheManager::clearContentCacheIfNeeded( $this->attribute( 'contentobject_id' ) );

        // clean up user cache
        if ( in_array( $object->attribute( 'contentclass_id' ), eZUser::contentClassIDs() ) )
        {
            eZUser::removeSessionData( $object->attribute( 'id' ) );
            eZUser::purgeUserCacheByUserId( $object->attribute( 'id' ) );
        }

        $parentNode = $this->attribute( 'parent' );
        if ( is_object( $parentNode ) )
        {
            eZContentCacheManager::clearContentCacheIfNeeded( $parentNode->attribute( 'contentobject_id' ) );
            $parentNode->updateAndStoreModified();
        }

        // Clean up policies and limitations
        eZRole::cleanupByNode( $this );

        // Clean up recent items
        eZContentBrowseRecent::removeRecentByNodeID( $nodeID );

        // Clean up bookmarks
        eZContentBrowseBookmark::removeByNodeID( $nodeID );

        // Clean up tip-a-friend counter
        eZTipafriendCounter::removeForNode( $nodeID );

        // Clean up view counter
        eZViewCounter::removeCounter( $nodeID );

        $db->commit();
    }

    /*!
     \static
     Returns information on what will happen if all subtrees in \a $deleteIDArray
     is removed. The returned structure is:
     - move_to_trash     - \c true if removed objects can be moved to trash,
                           some objects are not allowed to be in trash (e.g user).
     - total_child_count - The total number of children for all delete items
     - can_remove_all    - Will be set to \c true if all selected items can be removed, \c false otherwise
     - delete_list - A list of all subtrees that should be removed, structure:
     -- node               - The content node
     -- object             - The content object
     -- class              - The content class
     -- node_name          - The name of the node
     -- child_count        - Total number of child items below the node
     -- can_remove         - Boolean which tells if the user has permission to remove the node
     -- can_remove_subtree - Boolean which tells if the user has permission to remove items in the subtree
     -- new_main_node_id   - The new main node ID for the node if it needs to be moved, or \c false if not
     -- object_node_count  - The number of nodes the object has (before removal)
     -- sole_node_count    - The number of nodes in the subtree (excluding current) that does
                             not have multiple locations.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function subtreeRemovalInformation( $deleteIDArray )
    {
        return eZContentObjectTreeNode::removeSubtrees( $deleteIDArray, true, true );
    }

    /*!
     \static
     Will remove the nodes in the subtrees defined in \a $deleteIDArray,
     it will only remove the nodes unless there are no more nodes for
     an object in which case the object is removed too.

     \param $moveToTrash If \c true it will move the object to trash, if \c false
                         the object will be purged from the system.
     \param $infoOnly If set to \c true then it will not remove the subtree
                      but instead return information on what will happen
                      if it is removed. See subtreeRemovalInformation() for the
                      returned structure.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function removeSubtrees( $deleteIDArray, $moveToTrash = true, $infoOnly = false )
    {
        $moveToTrashAllowed = true;
        $deleteResult = array();
        $totalChildCount = 0;
        $totalLoneNodeCount = 0;
        $canRemoveAll = true;
        $hasPendingObject = false;

        $db = eZDB::instance();
        $db->begin();

        $userClassIDArray = eZUser::contentClassIDs();

        foreach ( $deleteIDArray as $deleteID )
        {
            $node = eZContentObjectTreeNode::fetch( $deleteID );
            if ( $node === null )
                continue;

            $object = $node->attribute( 'object' );
            if ( $object === null )
                continue;

            $class = $object->attribute( 'content_class' );
            $canRemove = $object->attribute( 'can_remove' );
            $canRemoveSubtree = true;

            $nodeID = $node->attribute( 'node_id' );
            $nodeName = $object->attribute( 'name' );

            $childCount = 0;
            $newMainNodeID = false;
            $objectNodeCount = 0;
            $readableChildCount = 0;

            if ( $canRemove )
            {
                $isUserClass = in_array( $class->attribute( 'id' ), $userClassIDArray );

                if ( $moveToTrashAllowed and $isUserClass )
                {
                    $moveToTrashAllowed = false;
                }
                $readableChildCount = $node->subTreeCount( array( 'Limitation' => array() ) );
                $childCount = $node->subTreeCount( array( 'IgnoreVisibility' => true ) );
                $totalChildCount += $childCount;

                $allAssignedNodes = $object->attribute( 'assigned_nodes' );
                $objectNodeCount = count( $allAssignedNodes );
                // We need to find a new main node ID if we are trying
                // to remove the current main node.
                if ( $node->attribute( 'main_node_id' ) == $nodeID )
                {
                    if ( count( $allAssignedNodes ) > 1 )
                    {
                        foreach( $allAssignedNodes as $assignedNode )
                        {
                            $assignedNodeID = $assignedNode->attribute( 'node_id' );
                            if ( $assignedNodeID == $nodeID )
                                continue;
                            $newMainNodeID = $assignedNodeID;
                            break;
                        }
                    }
                }

                if ( $infoOnly )
                {
                    // Find the number of items in the subtree we are allowed to remove
                    // if this differs from the total count it means we have items we cannot remove
                    // We do this by fetching the limitation list for content/remove
                    // and passing it to the subtree count function.
                    $currentUser = eZUser::currentUser();
                    $accessResult = $currentUser->hasAccessTo( 'content', 'remove' );
                    if ( $accessResult['accessWord'] == 'limited' )
                    {
                        $limitationList = $accessResult['policies'];
                        $removeableChildCount = $node->subTreeCount( array( 'Limitation' => $limitationList, 'IgnoreVisibility' => true ) );
                        $canRemoveSubtree = ( $removeableChildCount == $childCount );
                        $canRemove = $canRemoveSubtree;
                    }
                    //check if there is sub object in pending status
                    $limitCount = 100;
                    $offset = 0;
                    while( 1 )
                    {
                        $children = $node->subTree( array( 'Limitation' => array(),
                                                            'SortBy' => array( 'path' , false ),
                                                            'Offset' => $offset,
                                                            'Limit' => $limitCount,
                                                            'IgnoreVisibility' => true,
                                                            'AsObject' => false ) );
                        // fetch pending node assignment(pending object)
                        $idList = array();
                        //add node itself into idList
                        if( $offset === 0 )
                        {
                            $idList[] = $nodeID;
                        }
                        foreach( $children as $child )
                        {
                            $idList[] = $child['node_id'];
                        }

                        if( count( $idList ) === 0 )
                        {
                            break;
                        }
                        $pendingChildCount = eZNodeAssignment::fetchChildCountByVersionStatus( $idList,
                                                                                       eZContentObjectVersion::STATUS_PENDING );
                        if( $pendingChildCount !== 0 )
                        {
                            // there is pending object
                            $hasPendingObject = true;
                            break;
                        }
                        $offset += $limitCount;
                    }
                }

                // We will only remove the subtree if are allowed
                // and are told to do so.
                if ( $canRemove and !$infoOnly )
                {
                    $moveToTrashTemp = $moveToTrash;
                    if ( !$moveToTrashAllowed )
                        $moveToTrashTemp = false;

                    // Remove children, fetching them by 100 to avoid memory overflow.
                    // removeNodeFromTree -> removeThis handles cache clearing
                    while ( 1 )
                    {
                        // We should remove the latest subitems first,
                        // so we should fetch subitems sorted by 'path_string' DESC
                        $children = $node->subTree( array( 'Limitation' => array(),
                                                           'SortBy' => array( 'path' , false ),
                                                           'Limit' => 100,
                                                           'IgnoreVisibility' => true ) );
                        if ( !$children )
                            break;

                        foreach ( $children as $child )
                        {
                            $childObject = $child->attribute( 'object' );
                            $child->removeNodeFromTree( $moveToTrashTemp );
                            eZContentObject::clearCache();
                        }
                    }

                    $node->removeNodeFromTree( $moveToTrashTemp );
                }
            }
            if ( !$canRemove )
                $canRemoveAll = false;

            // Do not create info list if we are removing subtrees
            if ( !$infoOnly )
                continue;

            $soleNodeCount = $node->subtreeSoleNodeCount();
            $totalLoneNodeCount += $soleNodeCount;
            if ( $objectNodeCount <= 1 )
                ++$totalLoneNodeCount;

            $item = array( "nodeName" => $nodeName, // Backwards compatibility
                           "childCount" => $childCount, // Backwards compatibility
                           "additionalWarning" => '', // Backwards compatibility, this will always be empty
                           'node' => $node,
                           'object' => $object,
                           'class' => $class,
                           'node_name' => $nodeName,
                           'child_count' => $childCount,
                           'object_node_count' => $objectNodeCount,
                           'sole_node_count' => $soleNodeCount,
                           'can_remove' => $canRemove,
                           'can_remove_subtree' => $canRemoveSubtree,
                           'real_child_count' => $readableChildCount,
                           'new_main_node_id' => $newMainNodeID );
            $deleteResult[] = $item;
        }

        $db->commit();

        if ( !$infoOnly )
            return true;

        if ( $moveToTrashAllowed and $totalLoneNodeCount == 0 )
            $moveToTrashAllowed = false;

        return array( 'move_to_trash' => $moveToTrashAllowed,
                      'total_child_count' => $totalChildCount,
                      'can_remove_all' => $canRemoveAll,
                      'delete_list' => $deleteResult,
                      'has_pending_object' => $hasPendingObject,
                      'reverse_related_count' => eZContentObjectTreeNode::reverseRelatedCount( $deleteIDArray ) );
    }

    /*!
     \private
     \static
     Return reverse related count for specified node

     \param $nodeIDArray, array of node id's

     \return reverse related count.
    */
    static function reverseRelatedCount( $nodeIDArray )
    {
        // Select count of all elements having reverse relations. And ignore those items that don't relate to objects other than being removed.
        if ( $nodeIDArray === array() )
        {
            return 0;
        }

        foreach( $nodeIDArray as $nodeID )
        {
            $contentObjectTreeNode = eZContentObjectTreeNode::fetch( $nodeID, false, false );
            $tempPathString = $contentObjectTreeNode['path_string'];

            // Create WHERE section
            $pathStringArray[] = "tree.path_string like '$tempPathString%'";
            $path2StringArray[] = "tree2.path_string like '$tempPathString%'";
        }
        $path_strings = '( ' . implode( ' OR ', $pathStringArray ) . ' ) ';
        $path_strings_where = '( ' . implode( ' OR ', $path2StringArray ) . ' ) ';

        // Total count of sub items
        $db = eZDB::instance();
        $countOfItems = $db->arrayQuery( "SELECT COUNT( DISTINCT( tree.node_id ) ) as count
                                                  FROM  ezcontentobject_tree tree,  ezcontentobject obj,
                                                        ezcontentobject_link link LEFT JOIN ezcontentobject_tree tree2
                                                        ON link.from_contentobject_id = tree2.contentobject_id
                                                  WHERE $path_strings
                                                        and link.to_contentobject_id = tree.contentobject_id
                                                        and obj.id = link.from_contentobject_id
                                                        and obj.current_version = link.from_contentobject_version
                                                        and not $path_strings_where" );

        if ( $countOfItems )
        {
            return $countOfItems[0]['count'];
        }
    }

    /*!
     Will check if you are  removing the main node in which case it relocates
     the main node before removing it. It will also remove the object if there
     no more node assignments for it.
     \param $moveToTrash If \c true it will move the object to trash, if \c false
                         the object will be purged from the system.

     \note This uses remove() to do the actual node removal but has some extra logic
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removeNodeFromTree( $moveToTrash = true )
    {
        $nodeID = $this->attribute( 'node_id' );
        if ( $nodeID == $this->attribute( 'main_node_id' ) )
        {
            $object = $this->object();
            $assignedNodes = $object->attribute( 'assigned_nodes' );
            if ( count( $assignedNodes ) > 1 )
            {
                $newMainNode = false;
                foreach ( $assignedNodes as $assignedNode )
                {
                    $assignedNodeID = $assignedNode->attribute( 'node_id' );
                    if ( $assignedNodeID == $nodeID )
                        continue;
                    $newMainNode = $assignedNode;
                    break;
                }

                // We need to change the main node ID before we remove the current node
                $db = eZDB::instance();
                $db->begin();
                eZContentObjectTreeNode::updateMainNodeID( $newMainNode->attribute( 'node_id' ),
                                                           $object->attribute( 'id' ),
                                                           $object->attribute( 'current_version' ),
                                                           $newMainNode->attribute( 'parent_node_id' ) );
                $this->removeThis();
                $db->commit();
            }
            else
            {
                // This is the last assignment so we remove the object too
                $db = eZDB::instance();
                $db->begin();
                $this->removeThis();

                if ( $moveToTrash )
                {
                    // saving information about this node in ..trash_node table
                    $trashNode = eZContentObjectTrashNode::createFromNode( $this );
                    $db = eZDB::instance();
                    $db->begin();
                    $trashNode->storeToTrash();
                    $db->commit();
                    $object->removeThis();
                }
                else
                {
                    $object->purge();
                }
                $db->commit();
            }
        }
        else
        {
            $this->removeThis();
        }
    }

    /*!
     \return The number of nodes in the current subtree that have no other placements.
    */
    function subtreeSoleNodeCount( $params = array() )
    {
        $nodeID = $this->attribute( 'node_id' );
        $node = $this;

        $depth = false;
        if ( isset( $params['Depth'] ) && is_numeric( $params['Depth'] ) )
        {
            $depth = $params['Depth'];

        }

        $fromNode = $nodeID;

        $nodePath = null;
        $nodeDepth = 0;
        if ( count( $node ) != 0 )
        {
            $nodePath = $node->attribute( 'path_string' );
            $nodeDepth = $node->attribute( 'depth' );
        }

        $childPath = $nodePath;

        $db = eZDB::instance();
        $pathString = " ezcot.path_string like '$childPath%' and ";

        $notEqParentString = "ezcot.node_id != $fromNode";
        $depthCond = '';
        if ( $depth )
        {

            $nodeDepth += $depth;
            if ( isset( $params[ 'DepthOperator' ] ) && $params[ 'DepthOperator' ] == 'eq' )
            {
                $depthCond = ' ezcot.depth = ' . $nodeDepth . '';
                $notEqParentString = '';
            }
            else
                $depthCond = ' ezcot.depth <= ' . $nodeDepth . ' and ';
        }

        $tmpTableName = $db->generateUniqueTempTableName( 'eznode_count_%' );
        $db->createTempTable( "CREATE TEMPORARY TABLE $tmpTableName ( count int )" );
        $query = "INSERT INTO $tmpTableName
                  SELECT
                          count( ezcot.main_node_id ) AS count
                    FROM
                          ezcontentobject_tree ezcot,
                          ezcontentobject_tree ezcot_all
                    WHERE
                           $pathString
                           $depthCond
                           $notEqParentString
                           and ezcot.contentobject_id = ezcot_all.contentobject_id
                    GROUP BY ezcot_all.main_node_id
                    HAVING count( ezcot.main_node_id ) <= 1";

        $db->query( $query, eZDBInterface::SERVER_SLAVE );
        $query = "SELECT count( * ) AS count
                  FROM $tmpTableName";

        $rows = $db->arrayQuery( $query, array(), eZDBInterface::SERVER_SLAVE );
        $db->dropTempTable( "DROP TABLE $tmpTableName" );
        return $rows[0]['count'];
    }

    /*!
      Moves the node to the given node.
      \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function move( $newParentNodeID, $nodeID = 0 )
    {
        if ( $nodeID == 0 )
        {
            $node = $this;
            $nodeID = $node->attribute( 'node_id' );
        }
        else
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
        }

        $oldPath = $node->attribute( 'path_string' );
        $oldParentNodeID = $node->attribute( 'parent_node_id' );
        $newParentNodeID =(int) $newParentNodeID;
        if ( $oldParentNodeID != $newParentNodeID )
        {
            $node->updateAndStoreModified();
            // Who moves which content should be logged.
            $object = $node->object();
            eZAudit::writeAudit( 'content-move', array( 'Node ID' => $node->attribute( 'node_id' ),
                                                        'Old parent node ID' => $oldParentNodeID, 'New parent node ID' => $newParentNodeID,
                                                        'Object ID' => $object->attribute( 'id' ), 'Content Name' => $object->attribute( 'name' ),
                                                        'Comment' => 'Moved the node to the given node: eZContentObjectTreeNode::move()' ) );

            $newParentNode = eZContentObjectTreeNode::fetch( $newParentNodeID );
            $newParentPath = $newParentNode->attribute( 'path_string' );
            $newParentDepth = $newParentNode->attribute( 'depth' );
            $newPath = $newParentPath . $nodeID;
            $oldDepth = $node->attribute( 'depth' );

            $oldPathLength = strlen( $oldPath );
            $moveQuery = "UPDATE
                                 ezcontentobject_tree
                          SET
                                 parent_node_id = $newParentNodeID
                          WHERE
                                 node_id = $nodeID";
            $db = eZDB::instance();
            $subStringString = $db->subString( 'path_string', $oldPathLength );
            $newPathString   = $db->concatString( array( "'$newPath'", $subStringString ) );
            $moveQuery1 = "UPDATE
                                 ezcontentobject_tree
                           SET
                                 path_string = $newPathString,
                                 depth = depth + $newParentDepth - $oldDepth + 1
                           WHERE
                                 path_string LIKE '$oldPath%'";
            $db->begin();
            $db->query( $moveQuery );
            $db->query( $moveQuery1 );

            /// role system clean up
            // Clean up policies and limitations

            $expireRoleCache = false;

            $limitationsToFix = eZPolicyLimitation::findByType( 'SubTree', $node->attribute( 'path_string' ), false );
            if ( count( $limitationsToFix ) > 0 )
            {
                $limitationIDString = $db->generateSQLINStatement( $limitationsToFix, 'limitation_id' );
                $subStringString =  $db->subString( 'value', $oldPathLength );
                $newValue = $db->concatString( array( "'$newPath'", $subStringString ) );

                $query = "UPDATE
                                ezpolicy_limitation_value
                          SET
                                value = $newValue
                          WHERE
                                value LIKE '$oldPath%' AND $limitationIDString";

                $db->query( $query );

                $expireRoleCache = true;
            }

            // clean up limitations on role assignment level
            $countRows = $db->arrayQuery( "SELECT COUNT(*) AS row_count FROM ezuser_role WHERE limit_identifier='Subtree' AND limit_value LIKE '$oldPath%'" );
            $assignmentsToFixCount = $countRows[0]['row_count'];

            if ( $assignmentsToFixCount > 0 )
            {
                $subStringString =  $db->subString( 'limit_value', $oldPathLength );
                $newValue = $db->concatString( array( "'$newPath'", $subStringString ) );

                $db->query( "UPDATE
                                ezuser_role
                             SET
                                limit_value = $newValue
                             WHERE
                                limit_identifier='Subtree' AND limit_value LIKE '$oldPath%'"
                          );

               $expireRoleCache = true;
           }

            if ( $expireRoleCache )
            {
                eZRole::expireCache();
            }

            // Update "is_invisible" node attribute.
            $newNode = eZContentObjectTreeNode::fetch( $nodeID );
            eZContentObjectTreeNode::updateNodeVisibility( $newNode, $newParentNode );
            $db->commit();
        }
    }

    function checkAccess( $functionName, $originalClassID = false, $parentClassID = false, $returnAccessList = false, $language = false )
    {
        $classID = $originalClassID;
        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        // Fetch the ID of the language if we get a string with a language code
        // e.g. 'eng-GB'
        $originalLanguage = $language;
        if ( is_string( $language ) && strlen( $language ) > 0 )
        {
            $language = eZContentLanguage::idByLocale( $language );
        }
        else
        {
            $language = false;
        }

        // This will be filled in with the available languages of the object
        // if a Language check is performed.
        $languageList = false;

        // This will be filled if parent object is needed.
        $parentObject = false;

        $origFunctionName = $functionName;
        // The 'move' function simply reuses 'edit' for generic access
        // but adds another top-level check below
        // The original function is still available in $origFunctionName
        if ( $functionName == 'move' )
            $functionName = 'edit';

        // Manage locations depends if it's removal or not.
        if ( $functionName == 'can_add_location' ||
             $functionName == 'can_remove_location' )
        {
            $functionName = 'manage_locations';
        }

        $accessResult = $user->hasAccessTo( 'content' , $functionName );
        $accessWord = $accessResult['accessWord'];
        if ( $origFunctionName == 'can_remove_location' )
        {
            if ( $this->ParentNodeID <= 1 )
            {
                return 0;
            }
            $currentNode = eZContentObjectTreeNode::fetch( $this->ParentNodeID );
            if ( !$currentNode instanceof eZContentObjectTreeNode )
            {
                return 0;
            }
            $contentObject = $currentNode->attribute( 'object' );
        }
        else
        {
            $currentNode = $this;
            $contentObject = $this->attribute( 'object' );
        }

        /*
        // Uncomment this part if 'create' permissions should become implied 'edit'.
        // Merges in 'create' policies with 'edit'
        if ( $functionName == 'edit' &&
             !in_array( $accessWord, array( 'yes', 'no' ) ) )
        {
            // Add in create policies.
            $accessExtraResult = $user->hasAccessTo( 'content', 'create' );
            if ( $accessExtraResult['accessWord'] != 'no' )
            {
                $accessWord = $accessExtraResult['accessWord'];
                if ( isset( $accessExtraResult['policies'] ) )
                {
                    $accessResult['policies'] = array_merge( $accessResult['policies'],
                                                             $accessExtraResult['policies'] );
                }
                if ( isset( $accessExtraResult['accessList'] ) )
                {
                    $accessResult['accessList'] = array_merge( $accessResult['accessList'],
                                                               $accessExtraResult['accessList'] );
                }
            }
        }
        */

        if ( $origFunctionName == 'remove' or
             $origFunctionName == 'move' or
             $origFunctionName == 'can_remove_location' )
        {
            // We do not allow these actions on top-level nodes
            // - remove
            // - move
            if ( $this->ParentNodeID <= 1 )
            {
                return 0;
            }
        }

        if ( $classID === false )
        {
            $classID = $contentObject->attribute( 'contentclass_id' );
        }
        if ( $accessWord == 'yes' )
        {
            return 1;
        }
        else if ( $accessWord == 'no' )
        {
            if ( $functionName == 'edit' )
            {
                // Check if we have 'create' access under the main parent
                $object = $currentNode->object();
                if ( $object && $object->attribute( 'current_version' ) == 1 && !$object->attribute( 'status' ) )
                {
                    $mainNode = eZNodeAssignment::fetchForObject( $object->attribute( 'id' ), $object->attribute( 'current_version' ) );
                    $parentObj = $mainNode[0]->attribute( 'parent_contentobject' );
                    $result = $parentObj->checkAccess( 'create', $object->attribute( 'contentclass_id' ),
                                                       $parentObj->attribute( 'contentclass_id' ), false, $originalLanguage );
                    return $result;
                }
                else
                {
                    return 0;
                }
            }

            return 0;
        }
        else
        {
            $policies = $accessResult['policies'];
            $access = 'denied';

            foreach ( $policies as $pkey => $limitationArray )
            {
                if ( $access == 'allowed' )
                {
                    break;
                }

                $limitationList = array();
                if ( isset( $limitationArray['Subtree' ] ) )
                {
                    $checkedSubtree = false;
                }
                else
                {
                    $checkedSubtree = true;
                    $accessSubtree = false;
                }
                if ( isset( $limitationArray['Node'] ) )
                {
                    $checkedNode = false;
                }
                else
                {
                    $checkedNode = true;
                    $accessNode = false;
                }
                foreach ( $limitationArray as $key => $valueList  )
                {
                    $access = 'denied';
                    switch( $key )
                    {
                        case 'Class':
                        {
                            if ( $functionName == 'create' and
                                 !$originalClassID )
                            {
                                $access = 'allowed';
                            }
                            else if ( $functionName == 'create' and
                                      in_array( $classID, $valueList ) )
                            {
                                $access = 'allowed';
                            }
                            else if ( $functionName != 'create' and
                                      in_array( $contentObject->attribute( 'contentclass_id' ), $valueList ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $valueList );
                            }
                        } break;

                        case 'ParentClass':
                        {
                            if (  in_array( $contentObject->attribute( 'contentclass_id' ), $valueList ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $valueList );
                            }
                        } break;

                        case 'Section':
                        case 'User_Section':
                        {
                            if ( in_array( $contentObject->attribute( 'section_id' ), $valueList ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $valueList );
                            }
                        } break;

                        case 'Language':
                        {
                            $languageMask = 0;
                            // If we don't have a language list yet we need to fetch it
                            // and optionally filter out based on $language.
                            if ( $functionName == 'create' )
                            {
                                // If the function is 'create' we do not use the language_mask for matching.
                                if ( $language !== false )
                                {
                                    $languageMask = $language;
                                }
                                else
                                {
                                    // If the create is used and no language specified then
                                    // we need to match against all possible languages (which
                                    // is all bits set, ie. -1).
                                    $languageMask = -1;
                                }
                            }
                            else
                            {
                                if ( $language !== false )
                                {
                                    if ( $languageList === false )
                                    {
                                        $languageMask = $contentObject->attribute( 'language_mask' );
                                        // We are restricting language check to just one language
                                        $languageMask &= $language;
                                        // If the resulting mask is 0 it means that the user is trying to
                                        // edit a language which does not exist, ie. translating.
                                        // The mask will then become the language trying to edit.
                                        if ( $languageMask == 0 )
                                        {
                                            $languageMask = $language;
                                        }
                                    }
                                }
                                else
                                {
                                    $languageMask = -1;
                                }
                            }
                            // Fetch limit mask for limitation list
                            $limitMask = eZContentLanguage::maskByLocale( $valueList );
                            if ( ( $languageMask & $limitMask ) != 0 )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $valueList );
                            }
                        } break;

                        case 'Owner':
                        case 'ParentOwner':
                        {
                            // if limitation value == 2, anonymous limited to current session.
                            if ( in_array( 2, $valueList ) &&
                                 $user->isAnonymous() )
                            {
                                $createdObjectIDList = eZPreferences::value( 'ObjectCreationIDList' );
                                if ( $createdObjectIDList &&
                                     in_array( $contentObject->attribute( 'id' ), unserialize( $createdObjectIDList ) ) )
                                {
                                    $access = 'allowed';
                                }
                            }
                            else if ( $contentObject->attribute( 'owner_id' ) == $userID || $contentObject->attribute( 'id' ) == $userID )
                            {
                                $access = 'allowed';
                            }
                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array ( 'Limitation' => $key );
                            }
                        } break;

                        case 'Group':
                        case 'ParentGroup':
                        {
                            $access = $contentObject->checkGroupLimitationAccess( $valueList, $userID );

                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array ( 'Limitation' => $key,
                                                          'Required' => $valueList );
                            }
                        } break;

                        case 'State':
                        {
                            if ( count( array_intersect( $valueList, $contentObject->attribute( 'state_id_array' ) ) ) == 0 )
                            {
                                $access = 'denied';
                                $limitationList = array ( 'Limitation' => $key,
                                                          'Required' => $valueList );
                            }
                            else
                            {
                                $access = 'allowed';
                            }
                        } break;

                        case 'ParentDepth':
                        {
                            if ( in_array( $currentNode->attribute( 'depth' ), $valueList ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $valueList );
                            }
                        } break;

                        case 'Node':
                        {
                            $accessNode = false;
                            $mainNodeID = $currentNode->attribute( 'main_node_id' );
                            foreach ( $valueList as $nodeID )
                            {
                                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                                $limitationNodeID = $node['main_node_id'];
                                if ( $mainNodeID == $limitationNodeID )
                                {
                                    $access = 'allowed';
                                    $accessNode = true;
                                    break;
                                }
                            }
                            if ( $access != 'allowed' && $checkedSubtree && !$accessSubtree )
                            {
                                $access = 'denied';
                                // ??? TODO: if there is a limitation on Subtree, return two limitations?
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $valueList );
                            }
                            else
                            {
                                $access = 'allowed';
                            }
                            $checkedNode = true;
                        } break;

                        case 'Subtree':
                        {
                            $accessSubtree = false;
                            $path = $currentNode->attribute( 'path_string' );
                            $subtreeArray = $valueList;
                            foreach ( $subtreeArray as $subtreeString )
                            {
                                if ( strstr( $path, $subtreeString ) )
                                {
                                    $access = 'allowed';
                                    $accessSubtree = true;
                                    break;
                                }
                            }
                            if ( $access != 'allowed' && $checkedNode && !$accessNode )
                            {
                                $access = 'denied';
                                // ??? TODO: if there is a limitation on Node, return two limitations?
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $valueList );
                            }
                            else
                            {
                                $access = 'allowed';
                            }
                            $checkedSubtree = true;
                        } break;

                        case 'User_Subtree':
                        {
                            $path = $currentNode->attribute( 'path_string' );
                            $subtreeArray = $valueList;
                            foreach ( $subtreeArray as $subtreeString )
                            {
                                if ( strstr( $path, $subtreeString ) )
                                {
                                    $access = 'allowed';
                                }
                            }
                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $valueList );
                            }
                        } break;

                        default:
                        {
                            if ( strncmp( $key, 'StateGroup_', 11 ) === 0 )
                            {
                                if ( count( array_intersect( $valueList, $contentObject->attribute( 'state_id_array' ) ) ) == 0 )
                                {
                                    $access = 'denied';
                                    $limitationList = array ( 'Limitation' => $key,
                                                              'Required' => $valueList );
                                }
                                else
                                {
                                    $access = 'allowed';
                                }
                            }
                        }
                    }

                    if ( $access == 'denied' )
                    {
                        break;
                    }
                }

                $policyList[] = array( 'PolicyID' => $pkey,
                                       'LimitationList' => $limitationList );
            }
            if ( $access == 'denied' )
            {
                $accessList = array( 'FunctionRequired' => array ( 'Module' => 'content',
                                                                   'Function' => $origFunctionName,
                                                                   'ClassID' => $classID,
                                                                   'MainNodeID' => $currentNode->attribute( 'main_node_id' ) ),
                                     'PolicyList' => $policyList );
                return 0;
            }
            else
            {
                return 1;
            }
        }
    }

    // code-template::create-block: class-list-from-policy, is-node
    // code-template::auto-generated:START class-list-from-policy
    // This code is automatically generated from templates/classlistfrompolicy.ctpl
    // DO NOT EDIT THIS CODE DIRECTLY, CHANGE THE TEMPLATE FILE INSTEAD

    function classListFromPolicy( $policy, $allowedLanguageCodes = false )
    {
        $canCreateClassIDListPart = array();
        $hasClassIDLimitation = false;
        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        $object = false;
        if ( isset( $policy['ParentOwner'] ) )
        {
            if ( $object === false )
                $object = $this->attribute( 'object' );

            // if limitation value == 2, anonymous limited to current session.
            if ( in_array( 2, $policy['ParentOwner'] ) && $user->isAnonymous() )
            {
                $createdObjectIDList = eZPreferences::value( 'ObjectCreationIDList' );
                if ( !$createdObjectIDList ||
                     !in_array( $object->ID, unserialize( $createdObjectIDList ) ) )
                {
                    return array();
                }
            }
            else if ( $object->attribute( 'owner_id' ) != $userID &&
                      $object->ID != $userID )
            {
                return array();
            }
        }

        if ( isset( $policy['ParentGroup'] ) )
        {
            if ( $object === false )
                $object = $this->attribute( 'object' );

            $access = $object->checkGroupLimitationAccess( $policy['ParentGroup'], $userID );
            if ( $access !== 'allowed' )
            {
                return array();
            }
        }

        if ( isset( $policy['Class'] ) )
        {
            $canCreateClassIDListPart = $policy['Class'];
            $hasClassIDLimitation = true;
        }

        if ( isset( $policy['User_Section'] ) )
        {
            if ( $object === false )
                $object = $this->attribute( 'object' );

            if ( !in_array( $object->attribute( 'section_id' ), $policy['User_Section']  ) )
            {
                return array();
            }
        }

        if ( isset( $policy['User_Subtree'] ) )
        {
            $allowed = false;
            if ( $object === false )
                $object = $this->attribute( 'object' );

            $assignedNodes = $object->attribute( 'assigned_nodes' );
            foreach ( $assignedNodes as $assignedNode )
            {
                $path = $assignedNode->attribute( 'path_string' );
                foreach ( $policy['User_Subtree'] as $subtreeString )
                {
                    if ( strstr( $path, $subtreeString ) )
                    {
                        $allowed = true;
                        break;
                    }
                }
            }
            if( !$allowed )
            {
                return array();
            }
        }

        if ( isset( $policy['Section'] ) )
        {
            if ( $object === false )
                $object = $this->attribute( 'object' );

            if ( !in_array( $object->attribute( 'section_id' ), $policy['Section']  ) )
            {
                return array();
            }
        }

        if ( isset( $policy['ParentClass'] ) )
        {
            if ( $object === false )
                $object = $this->attribute( 'object' );

            if ( !in_array( $object->attribute( 'contentclass_id' ), $policy['ParentClass']  ) )
            {
                return array();
            }
        }

        if ( isset( $policy['ParentDepth'] ) && is_array( $policy['ParentDepth'] ) )
        {
            $NodeDepth = $this->attribute( 'depth' );
            if ( !in_array( '*', $policy['ParentDepth'] ) && !in_array( $NodeDepth, $policy['ParentDepth'] ) )
            {
                return array();
            }
        }

        if ( isset( $policy['Assigned'] ) )
        {
            if ( $object === false )
                $object = $this->attribute( 'object' );

            if ( $object->attribute( 'owner_id' ) != $userID )
            {
                return array();
            }
        }

        $allowedNode = false;
        if ( isset( $policy['Node'] ) )
        {
            $allowed = false;
            foreach( $policy['Node'] as $nodeID )
            {
                $mainNodeID = $this->attribute( 'main_node_id' );
                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                if ( $mainNodeID == $node['main_node_id'] )
                {
                    $allowed = true;
                    $allowedNode = true;
                    break;
                }
            }
            if ( !$allowed && !isset( $policy['Subtree'] ) )
            {
                return array();
            }
        }

        if ( isset( $policy['Subtree'] ) )
        {
            $allowed = false;
            if ( $object === false )
                $object = $this->attribute( 'object' );
            $assignedNodes = $object->attribute( 'assigned_nodes' );
            foreach ( $assignedNodes as $assignedNode )
            {
                $path = $assignedNode->attribute( 'path_string' );
                foreach ( $policy['Subtree'] as $subtreeString )
                {
                    if ( strstr( $path, $subtreeString ) )
                    {
                        $allowed = true;
                        break;
                    }
                }
            }
            if ( !$allowed && !$allowedNode )
            {
                return array();
            }
        }

        if ( isset( $policy['Language'] ) )
        {
            if ( $allowedLanguageCodes )
            {
                $allowedLanguageCodes = array_intersect( $allowedLanguageCodes, $policy['Language'] );
            }
            else
            {
                $allowedLanguageCodes = $policy['Language'];
            }
        }

        if ( $hasClassIDLimitation )
        {
            return array( 'classes' => $canCreateClassIDListPart, 'language_codes' => $allowedLanguageCodes );
        }
        return array( 'classes' => '*', 'language_codes' => $allowedLanguageCodes );
    }

    // This code is automatically generated from templates/classlistfrompolicy.ctpl
    // code-template::auto-generated:END class-list-from-policy

    // code-template::create-block: can-instantiate-class-list, group-filter, object-policy-list, name-create, object-creation, object-sql-creation
    // code-template::auto-generated:START can-instantiate-class-list
    // This code is automatically generated from templates/classcreatelist.ctpl
    // DO NOT EDIT THIS CODE DIRECTLY, CHANGE THE TEMPLATE FILE INSTEAD

    /*!
     Finds all classes that the current user can create objects from and returns.
     It is also possible to filter the list event more with \a $includeFilter and \a $groupList.

     \param $asObject If \c true then it return eZContentClass objects, if not it will
                      be an associative array with \c name and \c id keys.
     \param $includeFilter If \c true then it will include only from class groups defined in
                           \a $groupList, if not it will exclude those groups.
     \param $groupList An array with class group IDs that should be used in filtering, use
                       \c false if you do not wish to filter at all.
     \param $fetchID A unique name for the current fetch, this must be supplied when filtering is
                     used if you want caching to work.
    */
    function canCreateClassList( $asObject = false, $includeFilter = true, $groupList = false, $fetchID = false )
    {
        $ini = eZINI::instance();
        $groupArray = array();
        $languageCodeList = eZContentLanguage::fetchLocaleList();
        $allowedLanguages = array( '*' => array() );

        $user = eZUser::currentUser();
        $accessResult = $user->hasAccessTo( 'content' , 'create' );
        $accessWord = $accessResult['accessWord'];

        $classIDArray = array();
        $classList = array();
        $fetchAll = false;
        if ( $accessWord == 'yes' )
        {
            $fetchAll = true;
            $allowedLanguages['*'] = $languageCodeList;
        }
        else if ( $accessWord == 'no' )
        {
            // Cannnot create any objects, return empty list.
            return $classList;
        }
        else
        {
            $policies = $accessResult['policies'];
            foreach ( $policies as $policyKey => $policy )
            {
                $policyArray = $this->classListFromPolicy( $policy, $languageCodeList );
                if ( count( $policyArray ) == 0 )
                {
                    continue;
                }
                $classIDArrayPart = $policyArray['classes'];
                $languageCodeArrayPart = $policyArray['language_codes'];
                if ( $classIDArrayPart == '*' )
                {
                    $fetchAll = true;
                    $allowedLanguages['*'] = array_unique( array_merge( $allowedLanguages['*'], $languageCodeArrayPart ) );
                }
                else
                {
                    foreach( $classIDArrayPart as $class )
                    {
                        if ( isset( $allowedLanguages[$class] ) )
                        {
                            $allowedLanguages[$class] = array_unique( array_merge( $allowedLanguages[$class], $languageCodeArrayPart ) );
                        }
                        else
                        {
                            $allowedLanguages[$class] = $languageCodeArrayPart;
                        }
                    }
                    $classIDArray = array_merge( $classIDArray, array_diff( $classIDArrayPart, $classIDArray ) );
                }
            }
        }

        $db = eZDB::instance();

        $filterTableSQL = '';
        $filterSQL = '';
        // Create extra SQL statements for the class group filters.
        if ( is_array( $groupList ) )
        {
            if ( count( $groupList ) == 0 )
            {
                return $classList;
            }

            $filterTableSQL = ', ezcontentclass_classgroup ccg';
            $filterSQL = ( " AND" .
                           "      cc.id = ccg.contentclass_id AND" .
                           "      " );
            $filterSQL .= $db->generateSQLINStatement( $groupList, 'ccg.group_id', !$includeFilter, true, 'int' );
        }

        $classNameFilter = eZContentClassName::sqlFilter( 'cc' );

        if ( $fetchAll )
        {
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*, $classNameFilter[nameField]" : "cc.id, $classNameFilter[nameField]";
            $rows = $db->arrayQuery( "SELECT DISTINCT $fields " .
                                     "FROM ezcontentclass cc$filterTableSQL, $classNameFilter[from] " .
                                     "WHERE cc.version = " . eZContentClass::VERSION_STATUS_DEFINED . " $filterSQL AND $classNameFilter[where] " .
                                     "ORDER BY $classNameFilter[nameField] ASC" );
            $classList = eZPersistentObject::handleRows( $rows, 'eZContentClass', $asObject );
        }
        else
        {
            // If the constrained class list is empty we are not allowed to create any class
            if ( count( $classIDArray ) == 0 )
            {
                return $classList;
            }

            $classIDCondition = $db->generateSQLINStatement( $classIDArray, 'cc.id' );
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*, $classNameFilter[nameField]" : "cc.id, $classNameFilter[nameField]";
            $rows = $db->arrayQuery( "SELECT DISTINCT $fields " .
                                     "FROM ezcontentclass cc$filterTableSQL, $classNameFilter[from] " .
                                     "WHERE $classIDCondition AND" .
                                     "      cc.version = " . eZContentClass::VERSION_STATUS_DEFINED . " $filterSQL AND $classNameFilter[where] " .
                                     "ORDER BY $classNameFilter[nameField] ASC" );
            $classList = eZPersistentObject::handleRows( $rows, 'eZContentClass', $asObject );
        }

        if ( $asObject )
        {
            foreach ( $classList as $key => $class )
            {
                $id = $class->attribute( 'id' );
                if ( isset( $allowedLanguages[$id] ) )
                {
                    $languageCodes = array_unique( array_merge( $allowedLanguages['*'], $allowedLanguages[$id] ) );
                }
                else
                {
                    $languageCodes = $allowedLanguages['*'];
                }
                $classList[$key]->setCanInstantiateLanguages( $languageCodes );
            }
        }

        eZDebugSetting::writeDebug( 'kernel-content-class', $classList, "class list fetched from db" );
        return $classList;
    }

    // This code is automatically generated from templates/classcreatelist.ctpl
    // code-template::auto-generated:END can-instantiate-class-list

    static function makeObjectsArray( $array , $with_contentobject = true )
    {
        $retNodes = array();
        if ( !is_array( $array ) )
            return $retNodes;

        foreach ( $array as $node )
        {
            unset( $object );

            if( $node['node_id'] == 1 )
            {
                if( !array_key_exists( 'name', $node ) || !$node['name'] )
                    $node['name'] = ezpI18n::tr( 'kernel/content', 'Top Level Nodes' );
            }

            $object = new eZContentObjectTreeNode( $node );
            // If the name is not set it will be fetched later on when
            // getName()/attribute( 'name' ) is accessed.
            if ( isset( $node['name'] ) )
            {
                $object->setName( $node['name'] );
            }

            if ( isset( $node['class_serialized_name_list'] ) )
            {
                $node['class_name'] = eZContentClass::nameFromSerializedString( $node['class_serialized_name_list'] );
                $object->ClassName = $node['class_name'];
            }
            if ( isset( $node['class_identifier'] ) )
                $object->ClassIdentifier = $node['class_identifier'];

            if ( isset( $node['is_container'] ) )
                $object->ClassIsContainer = $node['is_container'];

            if ( $with_contentobject )
            {
                if ( isset( $node['class_name'] ) )
                {
                    unset( $node['remote_id'] );
                    $contentObject = new eZContentObject( $node );

                    $permissions = array();
                    $contentObject->setPermissions( $permissions );
                    $contentObject->setClassName( $node['class_name'] );
                    if ( isset( $node['class_identifier'] ) )
                        $contentObject->ClassIdentifier = $node['class_identifier'];

                }
                else
                {
                    $contentObject = new eZContentObject( array());
                    if ( isset( $node['name'] ) )
                         $contentObject->setCachedName( $node['name'] );
                }
                if ( isset( $node['real_translation'] ) && $node['real_translation'] != '' )
                {
                    $object->CurrentLanguage = $node['real_translation'];
                    $contentObject->CurrentLanguage = $node['real_translation'];
                }
                if ( $node['node_id'] == 1 )
                {
                    $contentObject->ClassName = 'Folder';
                    $contentObject->ClassIdentifier = 'folder';
                    $contentObject->ClassID = 1;
                    $contentObject->SectionID = 1;
                }

                $object->setContentObject( $contentObject );
            }
            $retNodes[] = $object;
        }
        return $retNodes;
    }

    /*!
     Get parent node id by node id
     \param $nodeID the node id you want parent node id for.
     */
    static function getParentNodeId( $nodeID )
    {
        if ( !isset( $nodeID ) )
            return null;
        $db = eZDB::instance();
        $nodeID =(int) $nodeID;
        $parentArr = $db->arrayQuery( "SELECT
                                              parent_node_id
                                       FROM
                                              ezcontentobject_tree
                                       WHERE
                                              node_id = $nodeID");
        return $parentArr[0]['parent_node_id'];
    }

    /**
     * Get parent node id's by content object id's.
     *
     * @static
     * @since Version 4.1
     * @param int|array $objectIDs
     * @param bool $groupByObjectId groups parent node ids by object id they belong to.
     * @param bool $onlyMainNode limits result to parent node id of main node.
     *
     * @return array Returns array of parent node id's
     */
    static function getParentNodeIdListByContentObjectID( $objectIDs, $groupByObjectId = false, $onlyMainNode = false )
    {
        if ( !$objectIDs )
            return null;
        if ( !is_array( $objectIDs ) )
            $objectIDs = array( $objectIDs );

        $db = eZDB::instance();
        $query = 'SELECT
                    parent_node_id, contentobject_id
                  FROM
                    ezcontentobject_tree
                  WHERE ' . $db->generateSQLINStatement( $objectIDs, 'contentobject_id', false, false, 'int' );

        if ( $onlyMainNode )
        {
            $query .= ' AND node_id = main_node_id';
        }

        $list = $db->arrayQuery( $query );
        $parentNodeIDs = array();
        if ( $groupByObjectId )
        {
            foreach( $list as $item )
            {
                $objectID = $item['contentobject_id'];
                if ( !isset( $parentNodeIDs[$objectID] ) )
                {
                    $parentNodeIDs[$objectID] = array();
                }
                $parentNodeIDs[$objectID][] = $item['parent_node_id'];
            }
        }
        else
        {
            foreach( $list as $item )
            {
                $parentNodeIDs[] = $item['parent_node_id'];
            }
        }
        return $parentNodeIDs;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function deleteNodeWhereParent( $node, $id )
    {
        eZContentObjectTreeNode::removeNode( eZContentObjectTreeNode::findNode( $node, $id ) );

    }

    static function findNode( $parentNode, $id, $asObject = false, $remoteID = false )
    {
        if ( !isset( $parentNode) || $parentNode == NULL  )
        {
            $parentNode = 2;
        }
        $parentNode =(int) $parentNode;
        $db = eZDB::instance();
        if( $asObject )
        {
            if ( $remoteID )
            {
                $objectIDFilter = 'ezcontentobject.remote_id = ' . (string) $id;
            }
            else
            {
                $objectIDFilter = 'contentobject_id = ' . (int) $id;
            }

            $query="SELECT ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.serialized_name_list as class_serialized_name_list,
                       ezcontentclass.identifier as class_identifier,
                       ezcontentclass.is_container as is_container
                FROM ezcontentobject_tree,
                     ezcontentobject,
                     ezcontentclass
                WHERE parent_node_id = $parentNode AND
                      $objectIDFilter AND
                      ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                      ezcontentclass.version=0  AND
                      ezcontentclass.id = ezcontentobject.contentclass_id ";

            $nodeListArray = $db->arrayQuery( $query );
            $retNodeArray = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );

            if ( count( $retNodeArray ) > 0 )
            {
                return $retNodeArray[0];
            }
            else
            {
                return null;
            }
        }
        else
        {
            $id = (int) $id;
            $getNodeQuery = "SELECT node_id
                           FROM ezcontentobject_tree
                           WHERE
                                parent_node_id=$parentNode AND
                                contentobject_id = $id ";
            $nodeArr = $db->arrayQuery( $getNodeQuery );
            if ( isset( $nodeArr[0] ) )
            {
                return $nodeArr[0]['node_id'];
            }
            else
            {
                return false;
            }
        }
    }

    function getName( $language = false )
    {
        // If the name is not set yet we fetch it from the object table
        if ( $this->Name === null || $language !== false )
        {
            if ( $this->CurrentLanguage || $language !== false )
            {
                $sql = "SELECT name FROM ezcontentobject_name WHERE contentobject_id=" . (int) $this->ContentObjectID . " AND content_version=" . (int)$this->attribute( 'contentobject_version' ) . " AND real_translation='" . ( $language !== false ? $language : $this->CurrentLanguage ) . "'";
            }
            else
            {
                $sql = "SELECT name FROM ezcontentobject WHERE id=" . (int) $this->ContentObjectID;
            }
            $db = eZDB::instance();
            $rows = $db->arrayQuery( $sql );
            if ( count( $rows ) > 0 )
            {
                if ( $language !== false )
                {
                    return $rows[0]['name'];
                }
                $this->Name = $rows[0]['name'];
            }
            else
            {
                if ( $language !== false )
                {
                    return false;
                }
                $this->Name = false;
            }
        }
        return $this->Name;
    }

    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
     \static
     Creates propper nodeassigment from contentNodeDOMNode specification

     \param contentobjecttreenode DOMNode
     \param contentobject.
     \param version
     \param isMain
     \param options

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function unserialize( $contentNodeDOMNode, $contentObject, $version, $isMain, &$nodeList, &$options, $handlerType = 'ezcontentobject' )
    {
        $parentNodeID = -1;

        $remoteID = $contentNodeDOMNode->getAttribute( 'remote-id' );
        $parentNodeRemoteID = $contentNodeDOMNode->getAttribute( 'parent-node-remote-id' );
        $node = eZContentObjectTreeNode::fetchByRemoteID( $remoteID );
        if ( is_object( $node ) )
        {
            $description = "Node with remote ID $remoteID already exists.";

            $choosenAction = eZPackageHandler::errorChoosenAction( eZContentObject::PACKAGE_ERROR_EXISTS,
                                                                   $options, $description, $handlerType, false );

            switch( $choosenAction )
            {
                // In case user have choosen "Keep existing object and create new"
                case eZContentObject::PACKAGE_NEW:
                {
                    $newRemoteID = eZRemoteIdUtility::generate( 'node' );
                    $node->setAttribute( 'remote_id', $newRemoteID );
                    $node->store();
                    $nodeInfo = array( 'contentobject_id' =>  $node->attribute( 'contentobject_id' ),
                                       'contentobject_version' => $node->attribute( 'contentobject_version' ),
                                       'parent_remote_id' => $remoteID );
                    $nodeAssignment = eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                                       null,
                                                                       $nodeInfo );
                    if ( is_object( $nodeAssignment ) )
                    {
                        $nodeAssignment->setAttribute( 'parent_remote_id', $newRemoteID );
                        $nodeAssignment->store();
                    }
                } break;

                // When running non-interactively with ezpm.php
                case eZPackage::NON_INTERACTIVE:
                case eZContentObject::PACKAGE_UPDATE:
                {
                    // Update existing node settigns.
                    if ( !$parentNodeRemoteID )
                    {
                        // when top node of subtree export, only update node sort field and sort order
                        $node->setAttribute( 'sort_field', eZContentObjectTreeNode::sortFieldID( $contentNodeDOMNode->getAttribute( 'sort-field' ) ) );
                        $node->setAttribute( 'sort_order', $contentNodeDOMNode->getAttribute( 'sort-order' ) );
                        $node->store();
                        return true;
                    }
                } break;

                default:
                {
                    // This error may occur only if data integrity is broken
                    $options['error'] = array( 'error_code' => eZContentObject::PACKAGE_ERROR_NODE_EXISTS,
                                               'element_id' => $remoteID,
                                               'description' => $description );
                    return false;
                } break;
            }
        }

        if ( $parentNodeRemoteID )
        {
            $parentNode = eZContentObjectTreeNode::fetchByRemoteID( $parentNodeRemoteID );
            if ( $parentNode !== null )
            {
                $parentNodeID = $parentNode->attribute( 'node_id' );
            }
        }
        else
        {
            if ( isset( $options['top_nodes_map'][$contentNodeDOMNode->getAttribute( 'node-id' )]['new_node_id'] ) )
            {
                $parentNodeID = $options['top_nodes_map'][$contentNodeDOMNode->getAttribute( 'node-id' )]['new_node_id'];
            }
            else if ( isset( $options['top_nodes_map']['*'] ) )
            {
                $parentNodeID = $options['top_nodes_map']['*'];
            }
            else
            {
                eZDebug::writeError( 'New parent node not set ' . $contentNodeDOMNode->getAttribute( 'name' ), __METHOD__ );
            }
        }

        $isMain = ( $isMain && $contentNodeDOMNode->getAttribute( 'is-main-node' ) );

        $nodeInfo = array( 'contentobject_id' => $contentObject->attribute( 'id' ),
                           'contentobject_version' => $version,
                           'is_main' => $isMain,
                           'parent_node' => $parentNodeID,
                           'parent_remote_id' => $remoteID,     // meaning processed node remoteID (not parent)
                           'sort_field' => eZContentObjectTreeNode::sortFieldID( $contentNodeDOMNode->getAttribute( 'sort-field' ) ),
                           'sort_order' => $contentNodeDOMNode->getAttribute( 'sort-order' ) );

        if ( $parentNodeID == -1 && $parentNodeRemoteID )
        {
            if ( !isset( $options['suspended-nodes'] ) )
            {
                $options['suspended-nodes'] = array();
            }

            $options['suspended-nodes'][$parentNodeRemoteID] = array( 'nodeinfo' => $nodeInfo,
                                                                      'priority' => $contentNodeDOMNode->getAttribute( 'priority' ) );
            return true;
        }

        $existNodeAssignment = eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                   null,
                                                   $nodeInfo );
        $nodeInfo['priority'] = $contentNodeDOMNode->getAttribute( 'priority' );
        if( !is_object( $existNodeAssignment ) )
        {
            $nodeAssignment = eZNodeAssignment::create( $nodeInfo );
            $nodeList[] = $nodeInfo;
            $nodeAssignment->store();
        }

        return true;
    }

    /*!
     Serialize ContentObjectTreeNode

     \params $options
     \params contentNodeIDArray
     \params topNodeIDArray
    */
    function serialize( $options, $contentNodeIDArray, $topNodeIDArray )
    {
        if ( $options['node_assignment'] == 'main' &&
             $this->attribute( 'main_node_id' ) != $this->attribute( 'node_id' ) )
        {
            return false;
        }
        if ( ! in_array( $this->attribute( 'node_id' ), array_keys( $contentNodeIDArray ) ) )
        {
            return false;
        }

        $dom = new DOMDocument( '1.0', 'utf-8' );

        $nodeAssignmentNode = $dom->createElement( 'node-assignment' );
        if ( $this->attribute( 'main_node_id' ) == $this->attribute( 'node_id' ) )
        {
            $nodeAssignmentNode->setAttribute( 'is-main-node', 1 );
        }
        if( !in_array( $this->attribute( 'node_id'), $topNodeIDArray ) )
        {
            $parentNode = $this->attribute( 'parent' );
            $nodeAssignmentNode->setAttribute( 'parent-node-remote-id', $parentNode->attribute( 'remote_id' ) );
        }
        $nodeAssignmentNode->setAttribute( 'name', $this->attribute( 'name' ) );
        $nodeAssignmentNode->setAttribute( 'node-id', $this->attribute( 'node_id' ) );
        $nodeAssignmentNode->setAttribute( 'remote-id', $this->attribute( 'remote_id' ) );
        $nodeAssignmentNode->setAttribute( 'sort-field', eZContentObjectTreeNode::sortFieldName( $this->attribute( 'sort_field' ) ) );
        $nodeAssignmentNode->setAttribute( 'sort-order', $this->attribute( 'sort_order' ) );
        $nodeAssignmentNode->setAttribute( 'priority', $this->attribute( 'priority' ) );
        return $nodeAssignmentNode;
    }

    /*!
     Update and store modified_subnode value for this node and all super nodes.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function updateAndStoreModified()
    {
        $pathString = trim( $this->attribute( 'path_string' ), '/' );

        // during publishing, a temporary path is generated. The update query shouldn't be executed as it doesn't affect anything
        if ( $pathString == 'TEMPPATH' )
            return;
        $pathArray = explode( '/', $pathString );

        if ( count( $pathArray ) > 0 )
        {
            $db = eZDB::instance();
            $db->query( 'UPDATE ezcontentobject_tree SET modified_subnode=' . time() .
                        ' WHERE ' . $db->generateSQLINStatement( $pathArray, 'node_id', false, true, 'int' ) );
        }
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function store( $fieldFilters = null )
    {
        $db = eZDB::instance();

        $db->begin();
        eZPersistentObject::store( $fieldFilters );
        $this->updateAndStoreModified();
        $db->commit();
    }

    function object()
    {
        if ( $this->hasContentObject() )
        {
            return $this->ContentObject;
        }
        $contentobject_id = $this->attribute( 'contentobject_id' );
        $obj = eZContentObject::fetch( $contentobject_id );
        $obj->setCurrentLanguage( $this->CurrentLanguage );
        $this->ContentObject = $obj;
        return $obj;
    }

    function hasContentObject()
    {
        if ( isset( $this->ContentObject ) && $this->ContentObject instanceof eZContentObject )
            return true;
        else
            return false;
    }

    /*!
     Sets the current content object for this node.
    */
    function setContentObject( $object )
    {
        $this->ContentObject = $object;
    }

    /*!
     \return the creator of the version published in the node.
    */
    function creator()
    {
        $db = eZDB::instance();
        $query = "SELECT creator_id
                  FROM ezcontentobject_version
                  WHERE
                        contentobject_id = '$this->ContentObjectID' AND
                        version = '$this->ContentObjectVersion' ";

        $creatorArray = $db->arrayQuery( $query );
        return eZContentObject::fetch( $creatorArray[0]['creator_id'] );
    }

    function contentObjectVersionObject( $asObject = true )
    {
        $version = eZContentObjectVersion::fetchVersion( $this->ContentObjectVersion, $this->ContentObjectID, $asObject );
        if ( $this->CurrentLanguage != false )
        {
            $version->CurrentLanguage = $this->CurrentLanguage;
        }
        return $version;
    }

    function urlAlias()
    {
        $useURLAlias =& $GLOBALS['eZContentObjectTreeNodeUseURLAlias'];
        $ini = eZINI::instance();
        $cleanURL = '';
        if ( !isset( $useURLAlias ) )
        {
            $useURLAlias = $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled';
        }
        if ( $useURLAlias )
        {
            $path = $this->pathWithNames();
            if ( $ini->hasVariable( 'SiteAccessSettings', 'PathPrefix' ) &&
                 $ini->variable( 'SiteAccessSettings', 'PathPrefix' ) != '' )
            {
                $prepend = $ini->variable( 'SiteAccessSettings', 'PathPrefix' );
                $pathIdenStr = substr( $prepend, strlen( $prepend ) -1 ) == '/'
                                ? $path . '/'
                                : $path;
                if ( strncasecmp( $pathIdenStr, $prepend, strlen( $prepend ) ) == 0 )
                    $cleanURL = eZURLAliasML::cleanURL( substr( $path, strlen( $prepend ) ) );
                else
                    $cleanURL = eZURLAliasML::cleanURL( $path );
            }
            else
            {
                $cleanURL = eZURLAliasML::cleanURL( $path );
            }
        }
        else
        {
            $cleanURL = eZURLAliasML::cleanURL( 'content/view/full/' . $this->NodeID );
        }

        return $cleanURL;
    }

    function url()
    {
        $ini = eZINI::instance();
        if ( $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
        {
            return $this->urlAlias();
        }
        return 'content/view/full/' . $this->NodeID;
    }


    /*!
     \return the cached value of the class identifier if it exists, if not it's fetched dynamically
    */
    public function classIdentifier()
    {
        if ( $this->ClassIdentifier === null )
        {
            $object = $this->object();
            $this->ClassIdentifier = $object->contentClassIdentifier();
        }

        return $this->ClassIdentifier;
    }

    /*!
     \return the cached value of the class name if it exists, if not it's fetched dynamically
    */
    public function className()
    {
        if ( $this->ClassName === null )
        {
            $object = $this->object();
            $class = $object->contentClass();
            $this->ClassName = $class->attribute( 'name' );
        }

        return $this->ClassName;
    }

    /*!
     \return the cached value of the class is_container flag if it exists, if not it's fetched dynamically
    */
    public function classIsContainer()
    {
        if ( $this->ClassIsContainer === null )
        {
            $object = $this->object();
            $class = $object->contentClass();
            $this->ClassIsContainer = $class->attribute( 'is_container' );
        }
        return $this->ClassIsContainer;
    }

    /*!
    \return combined string representation of both "is_hidden" and "is_invisible" attributes
    Used in the node view templates.
    FIXME: this method probably should be removed in the future.
    */
    function hiddenInvisibleString()
    {
        return ( $this->IsHidden ? 'H' : '-' ) . '/' . ( $this->IsInvisible ? 'X' : '-' );
    }

    /*!
    \return combined string representation of both "is_hidden" and "is_invisible" attributes
    Used in the limitation handling templates.
    */
    function hiddenStatusString()
    {
        if( $this->IsHidden )
        {
            return ezpI18n::tr( 'kernel/content', 'Hidden' );
        }
        else if( $this->IsInvisible )
        {
            return ezpI18n::tr( 'kernel/content', 'Hidden by superior' );
        }
        return ezpI18n::tr( 'kernel/content', 'Visible' );
    }

    /*!
     \a static

     \param $node            Root node of the subtree
     \param $modifyRootNode  Whether to modify the root node (true/false)

     Hide algorithm:
     if ( root node of the subtree is visible )
     {
        1) Mark root node as hidden and invisible
        2) Recursively mark child nodes as invisible except for ones which have been previously marked as invisible
     }
     else
     {
        Mark root node as hidden
     }

     In some cases we don't want to touch the root node when (un)hiding a subtree, for example
     after content/move or content/copy.
     That's why $modifyRootNode argument is used.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function hideSubTree( eZContentObjectTreeNode $node, $modifyRootNode = true )
    {
        $nodeID = $node->attribute( 'node_id' );
        $time = time();
        $db = eZDB::instance();

        $db->begin();

        if ( !$node->attribute( 'is_invisible' ) ) // if root node is visible
        {
            // 1) Mark root node as hidden and invisible.
            if ( $modifyRootNode )
                $db->query( "UPDATE ezcontentobject_tree SET is_hidden=1, is_invisible=1, modified_subnode=$time WHERE node_id=$nodeID" );

            // 2) Recursively mark child nodes as invisible, except for ones which have been previously marked as invisible.
            $nodePath = $node->attribute( 'path_string' );
            $db->query( "UPDATE ezcontentobject_tree SET is_invisible=1, modified_subnode=$time WHERE is_invisible=0 AND path_string LIKE '$nodePath%'" );
        }
        else
        {
            // Mark root node as hidden
            if ( $modifyRootNode )
                $db->query( "UPDATE ezcontentobject_tree SET is_hidden=1, modified_subnode=$time WHERE node_id=$nodeID" );
        }

        $node->updateAndStoreModified();

        $db->commit();

        eZContentObjectTreeNode::clearViewCacheForSubtree( $node, $modifyRootNode );
    }

    /*!
     \a static

     \param $node            Root node of the subtree
     \param $modifyRootNode  Whether to modify the root node (true/false)

     Unhide algorithm:
     if ( parent node is visible )
     {
        1) Mark root node as not hidden and visible.
        2) Recursively mark child nodes as visible (except for nodes previosly marked as hidden, and all their children).
     }
     else
     {
        Mark root node as not hidden.
     }

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function unhideSubTree( eZContentObjectTreeNode $node, $modifyRootNode = true )
    {
        $nodeID = $node->attribute( 'node_id' );
        $nodePath = $node->attribute( 'path_string' );
        $nodeInvisible = $node->attribute( 'is_invisible' );
        $parentNode = $node->attribute( 'parent' );
        $time = time();
        $db = eZDB::instance();

        $db->begin();

        if ( ! $parentNode->attribute( 'is_invisible' ) ) // if parent node is visible
        {
            // 1) Mark root node as not hidden and visible.
            if ( $modifyRootNode )
                $db->query( "UPDATE ezcontentobject_tree SET is_invisible=0, is_hidden=0, modified_subnode=$time WHERE node_id=$nodeID" );

            // 2) Recursively mark child nodes as visible (except for nodes previosly marked as hidden, and all their children).

            // 2.1) $hiddenChildren = Fetch all hidden children for the root node
            $hiddenChildren = $db->arrayQuery( "SELECT path_string FROM ezcontentobject_tree " .
                                                "WHERE node_id <> $nodeID AND is_hidden=1 AND path_string LIKE '$nodePath%'" );
            $skipSubtreesString = '';
            foreach ( $hiddenChildren as $i )
                $skipSubtreesString .= " AND path_string NOT LIKE '" . $i['path_string'] . "%'";

            // 2.2) Mark those children as visible which are not under nodes in $hiddenChildren
            $db->query( "UPDATE ezcontentobject_tree SET is_invisible=0, modified_subnode=$time WHERE path_string LIKE '$nodePath%' $skipSubtreesString" );
        }
        else
        {
            // Mark root node as not hidden.
            if ( $modifyRootNode )
                $db->query( "UPDATE ezcontentobject_tree SET is_hidden=0, modified_subnode=$time WHERE node_id=$nodeID" );
        }

        $node->updateAndStoreModified();

        $db->commit();

        eZContentObjectTreeNode::clearViewCacheForSubtree( $node, $modifyRootNode );
    }

    /*!
     \a static
     Depending on the new parent node visibility, recompute "is_invisible" attribute for the given node and its children.
     (used after content/move or content/copy)
    */
    static function updateNodeVisibility( $node, $parentNode, $recursive = true )
    {
        if ( !$node )
        {
            eZDebug::writeWarning( 'No such node to update visibility for.' );
            return;
        }

        if ( !$parentNode )
        {
            eZDebug::writeWarning( 'No parent node found when updating node visibility' );
            return;
        }

        if ( $node->attribute( 'is_hidden' ) == 0 &&
             $parentNode->attribute( 'is_invisible' ) != $node->attribute( 'is_invisible' ) )
        {
            $parentNodeIsVisible = $parentNode->attribute( 'is_invisible' );
            $nodeID                 = $node->attribute( 'node_id' );
            $db                     = eZDB::instance();
            $db->begin();
            $db->query( "UPDATE ezcontentobject_tree SET is_invisible=$parentNodeIsVisible WHERE node_id=$nodeID" );

            if ( $recursive )
            {
                // update visibility for children of the node
                if( $parentNodeIsVisible )
                    eZContentObjectTreeNode::hideSubTree( $node, $modifyRootNode = false );
                else
                    eZContentObjectTreeNode::unhideSubTree( $node, $modifyRootNode = false );
            }
            $db->commit();
        }
    }

    /*!
     \a static
     \return true on success, false otherwise
    */
    static function clearViewCacheForSubtree( eZContentObjectTreeNode $node, $clearForRootNode = true )
    {
        // Max nodes to fetch at a time
        static $limit = 50;

        if ( $clearForRootNode )
        {
            $objectID = $node->attribute( 'contentobject_id' );
            eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
        }

        $offset = 0;
        $params = array( 'AsObject' => false,
                         'Depth' => false,
                         'Limitation' => array() ); // Empty array means no permission checking
        $subtreeCount = $node->subTreeCount( $params );
        while ( $offset < $subtreeCount )
        {
            $params['Offset'] = $offset;
            $params['Limit'] = $limit;

            $subtreeChunk = $node->subTree( $params );
            $nNodesInChunk = count( $subtreeChunk );
            $offset += $nNodesInChunk;
            if ( $nNodesInChunk == 0 )
                break;

            $objectIDList = array();
            foreach ( $subtreeChunk as $curNode )
                $objectIDList[] = $curNode['contentobject_id'];
            $objectIDList = array_unique( $objectIDList );
            unset( $subtreeChunk );

            foreach ( $objectIDList as $objectID )
                eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
        }

        return true;
    }

    static function setVersionByObjectID( $objectID, $newVersion )
    {
        $db = eZDB::instance();
        $db->query( "UPDATE ezcontentobject_tree SET contentobject_version='$newVersion' WHERE contentobject_id='$objectID'" );
    }

    function currentLanguage()
    {
        return $this->CurrentLanguage;
    }

    function setCurrentLanguage( $languageCode )
    {
        $this->CurrentLanguage = $languageCode;
        if ( $this->hasContentObject() )
        {
            $this->ContentObject->setCurrentLanguage( $languageCode );
        }
        $this->Name = null;
    }

    /*!
     \static
    */
    static function parentDepthLimitationList()
    {
        $maxLevel = 0;
        $ini = eZINI::instance();
        if ( $ini->hasVariable( 'RoleSettings', 'MaxParentDepthLimitation' ) )
            $maxLevel = $ini->variable( 'RoleSettings', 'MaxParentDepthLimitation' );

        $depthArray = array();
        for ( $i = 1; $i <= $maxLevel; $i++ )
        {
            $depthArray[] = array( 'name' => $i, 'id' => $i );
        }

        return $depthArray;
    }

    /*
      Returns available classes as Js array.
      Checks if the node is container, if yes emptyStr will be returned.
    */
    function availableClassesJsArray()
    {
        return eZContentObjectTreeNode::availableClassListJsArray( array( 'node' => $this ) );
    }

    /*
      Returns available classes as Js array.
      Checks for ini settings.
    */
    static function availableClassListJsArray( $parameters = false )
    {
        $iniMenu = eZINI::instance( 'contentstructuremenu.ini' );
        $falseValue = "''";

        if ( $iniMenu->hasVariable( 'TreeMenu', 'CreateHereMenu' ) )
        {
            $createHereMenu = $iniMenu->variable( 'TreeMenu', 'CreateHereMenu' );
        }
        else
        {
            $createHereMenu = 'simplified';
        }

        if ( $createHereMenu != 'simplified' and $createHereMenu != 'full' )
            return $falseValue;

        $ini = eZINI::instance( 'content.ini' );
        list( $usersClassGroupID, $setupClassGroupID ) = $ini->variableMulti( 'ClassGroupIDs', array( 'Users', 'Setup' ) );
        $userRootNode = $ini->variable( 'NodeSettings', 'UserRootNode' );
        $groupIDs = false;
        $filterType = 'include';

        if ( !is_array( $parameters ) )
            return $falseValue;

        $node = isset( $parameters['node'] ) ? $parameters['node'] : false;
        if ( is_object( $node ) )
        {
            if ( $createHereMenu == 'full' and !$node->canCreate() )
                return $falseValue;

            $obj = $node->object();
            $contentClass = $obj->attribute( 'content_class' );
            if ( !$contentClass->attribute( 'is_container' ) )
            {
                return $falseValue;
            }

            $pathArray = $node->pathArray();
        }
        else
        {
            // If current object is not container we should not return class list, should not display "create here" menu.
            if ( isset( $parameters['is_container'] ) and !$parameters['is_container'] )
                return $falseValue;

            // Check if current user can create under this node
            if ( $createHereMenu == 'full' and isset( $parameters['node_id'] ) )
            {
                $node = eZContentObjectTreeNode::fetch( $parameters['node_id'] );
                if ( is_object( $node ) and !$node->canCreate() )
                    return $falseValue;
            }
            $pathString = isset( $parameters['path_string'] ) ? $parameters['path_string'] : false;
            if ( !$pathString )
                return $falseValue;

            $pathItems = explode( '/', $pathString );
            $pathArray = array();
            foreach ( $pathItems as $pathItem )
            {
                if ( $pathItem != '' )
                    $pathArray[] = (int) $pathItem;
            }
        }

        if ( in_array( $userRootNode, $pathArray ) )
        {
            $groupIDs = array( $usersClassGroupID );
        }
        else
        {
            $groupIDs = array( $usersClassGroupID, $setupClassGroupID );
            $filterType = 'exclude';
        }

        if ( $createHereMenu == 'simplified' )
        {
            $classes = eZContentClass::fetchAllClasses( false, $filterType == 'include', $groupIDs );
            return eZContentObjectTreeNode::getClassesJsArray( false, $filterType == 'include', $groupIDs, false, $classes );
        }

        return eZContentObjectTreeNode::getClassesJsArray( $node, $filterType == 'include', $groupIDs );
    }

    /*
      Returns available classes as Js array.
      \note building js array.
    */
    static function getClassesJsArray( $node = false, $includeFilter = true, $groupList = false, $fetchID = false, $classes = false )
    {
        $falseValue = "''";
        // If $classes is false we should check $node and fetch class list
        if ( $classes === false )
        {
            // If $node is object we should fetch available classes from node, from ezcontentclass otherwise
            $classes = ( $node instanceOf eZContentObjectTreeNode )
                        ? $node->canCreateClassList( false, $includeFilter, $groupList, $fetchID )
                        : eZContentClass::canInstantiateClassList( false, $includeFilter, $groupList, $fetchID );
        }
        if ( !is_array( $classes ) )
            return $falseValue;

        // Create javascript array
        $classList = array();
        foreach ( $classes as $class )
        {
            if ( $class instanceOf eZContentClass )
            {
                $classID = $class->attribute( 'id' );
                $className = $class->attribute( 'name' );
            }
            elseif ( is_array( $class ) )
            {
                $classID = $class['id'];
                $className = $class['name'];
            }
            $classList[] = array( 'classID' => (int) $classID,
                                  'name'    => htmlentities( $className, ENT_QUOTES ) );
        }

        if ( $classList )
            return json_encode( $classList );

        return $falseValue;
    }


    /// The current language for the node
    public $CurrentLanguage = false;

    /// Name of the node
    public $Name;

    /// Contains the cached value of the class identifier
    public $ClassIdentifier = null;
    public $ClassName = null;
    protected $ClassIsContainer = null;
}

?>
