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
$offset = $Params['Offset'];

$listLimitPreferenceName = 'admin_state_group_list_limit';
$listLimitPreferenceValue = eZPreferences::value( $listLimitPreferenceName );

switch( $listLimitPreferenceValue )
{
    case '2': { $limit = 25; } break;
    case '3': { $limit = 50; } break;
    default:  { $limit = 10; } break;
}

$languages = eZContentLanguage::fetchList();



$tpl = eZTemplate::factory();

eZDebug::writeDebug( $Module->currentAction() );
if ( $Module->isCurrentAction( 'Remove' ) && $Module->hasActionParameter( 'RemoveIDList' ) )
{
    $removeIDList = $Module->actionParameter( 'RemoveIDList' );

    foreach ( $removeIDList as $removeID )
    {
        $group = eZContentObjectStateGroup::fetchById( $removeID );
        if ( $group && !$group->isInternal() )
        {
            eZContentObjectStateGroup::removeByID( $removeID );
        }
    }
}
else if ( $Module->isCurrentAction( 'Create' ) )
{
    return $Module->redirectTo( 'state/group_edit' );
}

$groups = eZContentObjectStateGroup::fetchByOffset( $limit, $offset );
$groupCount = eZPersistentObject::count( eZContentObjectStateGroup::definition() );

$viewParameters = array( 'offset' => $offset );

$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'list_limit_preference_name', $listLimitPreferenceName );
$tpl->setVariable( 'list_limit_preference_value', $listLimitPreferenceValue );
$tpl->setVariable( 'groups', $groups );
$tpl->setVariable( 'group_count', $groupCount );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'languages', $languages );

$Result = array(
    'content' => $tpl->fetch( 'design:state/groups.tpl' ),
    'path'    => array(
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezpI18n::tr( 'kernel/state', 'Groups' ) )
    )
);

?>
