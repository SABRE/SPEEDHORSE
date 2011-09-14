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

$GroupIdentifier = $Params['GroupIdentifier'];
$Module = $Params['Module'];

$group = $GroupIdentifier === null ? new eZContentObjectStateGroup() : eZContentObjectStateGroup::fetchByIdentifier( $GroupIdentifier );

if ( !is_object( $group ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

if ( $group->isInternal() )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}



$tpl = eZTemplate::factory();

$currentAction = $Module->currentAction();

if ( $currentAction == 'Cancel' )
{
    return $Module->redirectTo( 'state/groups' );
}
else if ( $currentAction == 'Store' )
{
    $group->fetchHTTPPersistentVariables();

    $messages = array();
    $isValid = $group->isValid( $messages );

    if ( $isValid )
    {
        $group->store();
        if ( $GroupIdentifier === null )
        {
            return $Module->redirectTo( 'state/group/' . $group->attribute( 'identifier' ) );
        }
        else
        {
            return $Module->redirectTo( 'state/groups' );
        }
    }

    $tpl->setVariable( 'is_valid', $isValid );
    $tpl->setVariable( 'validation_messages', $messages );
}

$tpl->setVariable( 'group', $group );

if ( $GroupIdentifier === null )
{
    $path = array(
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'New group' ) )
    );
}
else
{
    $path = array(
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'Group edit' ) ),
        array( 'url' => false, 'text' => $group->attribute( 'identifier' ) )
    );
}

$Result = array(
    'content' => $tpl->fetch( 'design:state/group_edit.tpl' ),
    'path'    => $path
);

?>
