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
$ClassID = null;
if ( isset( $Params["ClassID"] ) )
    $ClassID = $Params["ClassID"];

$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID = $Params["GroupID"];

$class = eZContentClass::fetch( $ClassID );
$ClassName = $class->attribute( 'name' );
$classObjects = eZContentObject::fetchSameClassList( $ClassID );
$ClassObjectsCount = count( $classObjects );
if ( $ClassObjectsCount == 0 )
    $ClassObjectsCount .= " object";
else
    $ClassObjectsCount .= " objects";
$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    $class->remove( true );
    eZContentClassClassGroup::removeClassMembers( $ClassID, 0 );
    $Module->redirectTo( '/class/classlist/' . $GroupID );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/class/classlist/' . $GroupID );
}
$Module->setTitle( "Deletion of class " .$ClassID );
$tpl = eZTemplate::factory();


$tpl->setVariable( "module", $Module );
$tpl->setVariable( "GroupID", $GroupID );
$tpl->setVariable( "ClassID", $ClassID );
$tpl->setVariable( "ClassName", $ClassName );
$tpl->setVariable( "ClassObjectsCount", $ClassObjectsCount );
$Result = array();
$Result['content'] = $tpl->fetch( "design:class/delete.tpl" );
$Result['path'] = array( array( 'url' => '/class/delete/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Remove class' ) ) );
?>
