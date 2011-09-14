<?php
//
// Created on: <16-Apr-2002 11:00:12 amos>
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
$GroupID = false;
if ( isset( $Params["GroupID"] ) )
    $GroupID = $Params["GroupID"];

$http = eZHTTPTool::instance();
$http->setSessionVariable( 'FromGroupID', $GroupID );
if ( $http->hasPostVariable( "RemoveButton" ) )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        if ( $deleteIDArray !== null )
        {
            $http->setSessionVariable( 'DeleteClassIDArray', $deleteIDArray );
            $Module->redirectTo( $Module->functionURI( 'removeclass' ) . '/'  . $GroupID . '/' );
        }
    }
}

if ( $http->hasPostVariable( "NewButton" ) )
{
    if ( $http->hasPostVariable( "CurrentGroupID" ) )
        $GroupID = $http->postVariable( "CurrentGroupID" );
    if ( $http->hasPostVariable( "CurrentGroupName" ) )
        $GroupName = $http->postVariable( "CurrentGroupName" );
    if ( $http->hasPostVariable( "ClassLanguageCode" ) )
        $LanguageCode = $http->postVariable( "ClassLanguageCode" );
    $params = array( null, $GroupID, $GroupName, $LanguageCode );
    $unorderedParams = array( 'Language' => $LanguageCode );
    $Module->run( 'edit', $params, $unorderedParams );
    return;
}

if ( !isset( $TemplateData ) or !is_array( $TemplateData ) )
{
    $TemplateData = array( array( "name" => "groupclasses",
                                  "http_base" => "ContentClass",
                                  "data" => array( "command" => "groupclass_list",
                                                   "type" => "class" ) ) );
}

$Module->setTitle( ezpI18n::tr( 'kernel/class', 'Class list of group' ) . ' ' . $GroupID );
$tpl = eZTemplate::factory();

$user = eZUser::currentUser();
foreach( $TemplateData as $tpldata )
{
    $tplname = $tpldata["name"];

    $groupInfo =  eZContentClassGroup::fetch( $GroupID );

    if( !$groupInfo )
    {
       return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }

    $list = eZContentClassClassGroup::fetchClassList( 0, $GroupID, $asObject = true );
    $groupModifier = eZContentObject::fetch( $groupInfo->attribute( 'modifier_id') );
    $tpl->setVariable( $tplname, $list );
    $tpl->setVariable( "class_count", count( $list ) );
    $tpl->setVariable( "GroupID", $GroupID );
    $tpl->setVariable( "group", $groupInfo );
    $tpl->setVariable( "group_modifier", $groupModifier );
}

$group = eZContentClassGroup::fetch( $GroupID );
$groupName = $group->attribute( 'name' );


$tpl->setVariable( "module", $Module );

$Result = array();
$Result['content'] = $tpl->fetch( "design:class/classlist.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Class groups' ) ),
                         array( 'url' => false,
                                'text' => $groupName ) );
?>
