<?php
//
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
$GroupIdentifier = $Params['GroupIdentifier'];
$LanguageCode = $Params['Language'];

$group = eZContentObjectStateGroup::fetchByIdentifier( $GroupIdentifier );

if ( !is_object( $group ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}



$tpl = eZTemplate::factory();

$currentAction = $Module->currentAction();

if ( !$group->isInternal() )
{
    if ( $currentAction == 'Remove' && $Module->hasActionParameter( 'RemoveIDList' ) )
    {
        $removeIDList = $Module->actionParameter( 'RemoveIDList' );
        $group->removeStatesByID( $removeIDList );
    }
    else if ( $currentAction == 'Edit' )
    {
        return $Module->redirectTo( "state/group_edit/$GroupIdentifier" );
    }
    else if ( $currentAction == 'Create' )
    {
        return $Module->redirectTo( "state/edit/$GroupIdentifier" );
    }
    else if ( $currentAction == 'UpdateOrder' && $Module->hasActionParameter( 'Order' ) )
    {
        $orderArray = $Module->actionParameter( 'Order' );
        asort( $orderArray );
        $stateIDList = array_keys( $orderArray );

        $group->reorderStates( $stateIDList );
    }
}

if ( $LanguageCode )
{
    $group->setCurrentLanguage( $LanguageCode );
}

$tpl->setVariable( 'group', $group );

$Result = array(
    'path' => array(
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'State' ) ),
        array( 'url' => 'state/groups', 'text' => ezpI18n::tr( 'kernel/state', 'Groups' ) ),
        array( 'url' => false, 'text' => $group->attribute( 'current_translation' )->attribute( 'name' ) )
    ),
    'content' => $tpl->fetch( 'design:state/group.tpl' )
)
?>
