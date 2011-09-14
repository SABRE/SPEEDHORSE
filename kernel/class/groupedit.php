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
$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID = $Params["GroupID"];

if ( is_numeric( $GroupID ) )
{
    $classgroup = eZContentClassGroup::fetch( $GroupID );
}
else
{
    $user = eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $classgroup = eZContentClassGroup::create( $user_id );
    $classgroup->setAttribute( "name", ezpI18n::tr( 'kernel/class/groupedit', "New Group" ) );
    $classgroup->store();
    $GroupID = $classgroup->attribute( "id" );
    $Module->redirectTo( $Module->functionURI( "groupedit" ) . "/" . $GroupID );
    return;
}

$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "DiscardButton" ) )
{
    $Module->redirectTo( $Module->functionURI( "grouplist" ) );
    return;
}

if ( $http->hasPostVariable( "StoreButton" ) )
{
    if ( $http->hasPostVariable( "Group_name" ) )
    {
        $name = $http->postVariable( "Group_name" );
    }
    $classgroup->setAttribute( "name", $name );
    // Set new modification date
    $date_time = time();
    $classgroup->setAttribute( "modified", $date_time );
    $user = eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );
    $classgroup->setAttribute( "modifier_id", $user_id );
    $classgroup->store();

    eZContentClassClassGroup::update( null, $GroupID, $name );

    $Module->redirectToView( 'classlist', array( $classgroup->attribute( 'id' ) ) );
    return;
}

$Module->setTitle( "Edit class group " . $classgroup->attribute( "name" ) );

// Template handling
$tpl = eZTemplate::factory();

$res = eZTemplateDesignResource::instance();
$res->setKeys( array( array( "classgroup", $classgroup->attribute( "id" ) ) ) );

$tpl->setVariable( "http", $http );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "classgroup", $classgroup );

$Result = array();
$Result['content'] = $tpl->fetch( "design:class/groupedit.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Class groups' ) ),
                         array( 'url' => false,
                                'text' => $classgroup->attribute( 'name' ) ) );

?>
