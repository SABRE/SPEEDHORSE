<?php
//
// Created on: <15-Jan-2010 13:06:01 ls>
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



$ini = eZINI::instance( 'dashboard.ini' );
$currentUser = eZUser::currentUser();

$orderedBlocks = array();

$dashboardBlocks = $ini->variable( 'DashboardSettings', 'DashboardBlocks' );

foreach( $dashboardBlocks as $blockIdentifier )
{
    $blockGroupName = 'DashboardBlock_' . $blockIdentifier;
    if ( !$ini->hasGroup( $blockGroupName ) )
        continue;

    $hasAccess = true;
    if ( $ini->hasVariable( $blockGroupName, 'PolicyList' ) )
    {
        $policyList = $ini->variable( $blockGroupName, 'PolicyList' );
        foreach( $policyList as $policy )
        {
            // Value is either "<node_id>" or "<module>/<function>"
            if ( strpos( $policy, '/' ) !== false )
            {
                list( $module, $function ) = explode( '/', $policy );
                    $result = $currentUser->hasAccessTo( $module, $function );

                if ( $result['accessWord'] === 'no' )
                {
                    $hasAccess = false;
                    break;
                }
            }
            else
            {
                $node = eZContentObjectTreeNode::fetch( $policy );
                if ( !$node instanceof eZContentObjectTreeNode || !$node->attribute('can_read') )
                {
                    $hasAccess = false;
                    break;
                }
            }
        }
    }

    if ( $hasAccess === false )
        continue;

    $priority = 0;
    if ( $ini->hasVariable( $blockGroupName, 'Priority' ) )
        $priority = $ini->variable( $blockGroupName, 'Priority' );

    $numberOfItems = null;
    if ( $ini->hasVariable( $blockGroupName, 'NumberOfItems' ) )
        $numberOfItems = $ini->variable( $blockGroupName, 'NumberOfItems' );

    $template = null;
    if ( $ini->hasVariable( $blockGroupName, 'Template' ) )
        $template = $ini->variable( $blockGroupName, 'Template' );

    while( isset( $orderedBlocks[$priority]  ) )
        $priority++;

    $orderedBlocks[$priority] = array( 'identifier' => $blockIdentifier,
                                       'template' => $template,
                                       'number_of_items' => $numberOfItems );
}

// Sort $orderedBlocks by key, starting from the lowest priority
ksort( $orderedBlocks );

$contentInfoArray = array();

$tpl = eZTemplate::factory();

$tpl->setVariable( 'blocks', $orderedBlocks );
$tpl->setVariable( 'user', $currentUser );
$tpl->setVariable( 'persistent_variable', false );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/dashboard.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Dashboard' ),
                                'url' => false ) );

$contentInfoArray['persistent_variable'] = false;
if ( $tpl->variable( 'persistent_variable' ) !== false )
    $contentInfoArray['persistent_variable'] = $tpl->variable( 'persistent_variable' );

$Result['content_info'] = $contentInfoArray;

?>
