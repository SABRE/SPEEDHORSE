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
$StateIdentifier = $Params['StateIdentifier'];
$LanguageCode = $Params['Language'];

$group = eZContentObjectStateGroup::fetchByIdentifier( $GroupIdentifier );

if ( !is_object( $group ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$state = $group->stateByIdentifier( $StateIdentifier );

if ( !is_object( $state ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$currentAction = $Module->currentAction();

if ( $currentAction == 'Edit' )
{
    return $Module->redirectTo( "state/edit/$GroupIdentifier/$StateIdentifier" );
}

if ( $LanguageCode )
{
    $state->setCurrentLanguage( $LanguageCode );
}



$tpl = eZTemplate::factory();
$tpl->setVariable( 'group', $group );
$tpl->setVariable( 'state', $state );

$Result = array(
    'content' => $tpl->fetch( 'design:state/view.tpl' ),
    'path' => array(
        array( 'url' => false,
               'text' => ezpI18n::tr( 'kernel/state', 'State' ) ),
        array( 'url' => 'state/group/' . $group->attribute( 'identifier' ),
               'text' => $group->attribute( 'identifier' ) ),
        array( 'url' => false,
               'text' => $state->attribute( 'identifier' ) )
    )
);

?>
