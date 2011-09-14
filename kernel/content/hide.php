<?php
//
// Created on: <22-Sen-2004 13:19:58 vs>
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

$Module = $Params['Module'];
$NodeID = $Params['NodeID'];

$curNode = eZContentObjectTreeNode::fetch( $NodeID );
if ( !$curNode )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

if ( !$curNode->attribute( 'can_hide' ) )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

if ( eZOperationHandler::operationIsAvailable( 'content_hide' ) )
{
    $operationResult = eZOperationHandler::execute( 'content',
                                                    'hide',
                                                     array( 'node_id' => $NodeID ),
                                                     null, true );
}
else
{
    eZContentOperationCollection::changeHideStatus( $NodeID );
}


$hasRedirect = eZRedirectManager::redirectTo( $Module, false );
if ( !$hasRedirect )
{
    // redirect to the parent node
    if( ( $parentNodeID = $curNode->attribute( 'parent_node_id' ) ) == 1 )
        $redirectNodeID = $NodeID;
    else
        $redirectNodeID = $parentNodeID;
    return $Module->redirectToView( 'view', array( 'full', $redirectNodeID ) );
}

?>
