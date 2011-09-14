<?php
//
// Adding to notifications
//
// Created on: <24-Jan-2005 17:48 rl>
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

$module = $Params['Module'];
$http = eZHTTPTool::instance();

//$Offset = $Params['Offset'];
//$viewParameters = array( 'offset' => $Offset );

//$nodeID = $http->postVariable( 'ContentNodeID' );
$nodeID = $Params['ContentNodeID'];
$user = eZUser::currentUser();

$redirectURI = $http->postVariable( 'RedirectURI', $http->sessionVariable( 'LastAccessesURI', '/' ) );

$viewMode = $http->hasPostVariable( 'ViewMode' ) ? $http->postVariable( 'ViewMode' ) : 'full';

if ( !$user->isLoggedIn() )
{
    eZDebug::writeError( 'User not logged in trying to subscribe for notification, node ID: ' . $nodeID,
                         'kernel/content/action.php' );
    $module->redirectTo( $redirectURI );
    return;
}

$contentNode = eZContentObjectTreeNode::fetch( $nodeID );
if ( !$contentNode )
{
    eZDebug::writeError( 'The nodeID parameter was empty, user ID: ' . $user->attribute( 'contentobject_id' ),
                         'kernel/content/action.php' );
    $module->redirectTo( $redirectURI );
    return;
}
if ( !$contentNode->attribute( 'can_read' ) )
{
    eZDebug::writeError( 'User does not have access to subscribe for notification, node ID: ' . $nodeID . ', user ID: ' . $user->attribute( 'contentobject_id' ),
                         'kernel/content/action.php' );
    $module->redirectTo( $redirectURI );
    return;
}

$tpl = eZTemplate::factory();
if ( $http->hasSessionVariable( "LastAccessesURI", false ) )
    $tpl->setVariable( 'redirect_url', $http->sessionVariable( "LastAccessesURI" ) );
//else
//    $tpl->setVariable( 'redirect_url', $module->functionURI( 'view' ) . '/full/2' );

$alreadyExists = true;

$nodeIDList = eZSubtreeNotificationRule::fetchNodesForUserID( $user->attribute( 'contentobject_id' ), false );
if ( !in_array( $nodeID, $nodeIDList ) )
{
    $rule = eZSubtreeNotificationRule::create( $nodeID, $user->attribute( 'contentobject_id' ) );
    $rule->store();
    $alreadyExists = false;
}
$tpl->setVariable( 'already_exists', $alreadyExists );
$tpl->setVariable( 'node_id', $nodeID );


$Result = array();
$Result['content'] = $tpl->fetch( 'design:notification/addingresult.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/notification', ($alreadyExists ? 'Notification already exists.' : 'Notification was added successfully!') ),
                                'url' => false ) );

?>
