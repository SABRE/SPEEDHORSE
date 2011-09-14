<?php
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


    include_once ('lib/ezutils/classes/ezfunctionhandler.php');
    include_once ('lib/ezutils/classes/ezsys.php');
    include_once( 'kernel/common/template.php' );
    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
    include_once( "lib/ezutils/classes/ezhttptool.php" );

    $tpl = templateInit();
    $http = eZHTTPTool::instance();

    if ( $http->hasPostVariable( 'Username' ) );
        $username = $http->postVariable( 'Username' );

    if ( $http->hasPostVariable( 'Password' ) );
        $password = $http->postVariable( 'Password' );

    if ( $http->hasPostVariable( 'NodeID' ) );
        $parentNodeID = $http->postVariable( 'NodeID' );

    // User authentication
    $user = eZUser::loginUser( $username, $password );
    if ( $user == false )
    {
        print( 'problem:Authentication failed' );
        eZExecution::cleanExit();
    }
    else
    {
        // Print the list of ID nodes..
        //Structure : name, type, ID
        $nodes = eZFunctionHandler::execute( 'content','list', array( 'parent_node_id' => $parentNodeID ) );

        $array = array();
        foreach( $nodes as $node )
        {
            $tpl->setVariable( 'node', $node );

            $nodeID = $node->attribute( 'node_id' );
            $name = $node->attribute( 'name' );
            $className = $node->attribute( 'class_name' );
            $object =& $node->object();
            $contentClass = $object->contentClass();
            $isContainer = $contentClass->attribute( 'is_container' );

            preg_match( '/\/+[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#;,]*[a-z0-9\/]{1}/si', $tpl->fetch( 'design:ezodf/icon.tpl' ), $matches );
            $iconPath = 'http://'. eZSys::hostname(). ':' . eZSys::serverPort() . $matches[0];
            $array[] = array( $nodeID, $name, $className, $isContainer, $iconPath );
        }

        //Test if not empty
        if ( empty( $array ) )
        {
            print( 'problem:No Items' );
            eZExecution::cleanExit();
        }

        //Convert the array into a string and display it
        $display = '';
        foreach( $array as $line )
        {
            foreach( $line as $element )
            {
                $display .= $element . ';';
            }
            $display .= chr( 13 );
        }

        print( $display );

        // Don't display eZ Publish page structure
        eZExecution::cleanExit();
    }
?>
