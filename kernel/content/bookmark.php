<?php
//
// Definition of Bookmark class
//
// Created on: <30-Apr-2003 13:46:01 sp>
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

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$user = eZUser::currentUser();
$userID = $user->id();

if ( $Module->isCurrentAction( 'Remove' )  )
{
    if ( $Module->hasActionParameter( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $Module->actionParameter( 'DeleteIDArray' );

        foreach ( $deleteIDArray as $deleteID )
        {
            $bookmark = eZContentBrowseBookmark::fetch( $deleteID );
            if ( $bookmark === null )
                continue;
            if ( $bookmark->attribute( 'user_id' ) == $userID )
                $bookmark->remove();
        }
    }
    if ( $http->hasPostVariable( 'NeedRedirectBack' ) )
    {
        return $Module->redirectTo( $http->postVariable( 'RedirectURI', $http->sessionVariable( 'LastAccessesURI', '/' ) ) );
    }
}
else if ( $Module->isCurrentAction( 'Add' )  )
{
    return eZContentBrowse::browse( array( 'action_name' => 'AddBookmark',
                                           'description_template' => 'design:content/browse_bookmark.tpl',
                                           'from_page' => "/content/bookmark" ),
                                    $Module );
}
else if ( $Module->isCurrentAction( 'AddBookmark' )  )
{
    $nodeList = eZContentBrowse::result( 'AddBookmark' );
    if ( $nodeList )
    {
        $db = eZDB::instance();
        $db->begin();
        foreach ( $nodeList as $nodeID )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( $node )
            {
                $nodeName = $node->attribute( 'name' );
                eZContentBrowseBookmark::createNew( $userID, $nodeID, $nodeName );
            }
        }
        $db->commit();
    }
}

$tpl = eZTemplate::factory();
$tpl->setVariable('view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/bookmark.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'My bookmarks' ),
                                'url' => false ) );


?>
