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
$http = eZHTTPTool::instance();
$deleteIDArray = $http->hasSessionVariable( 'DeleteGroupIDArray' ) ? $http->sessionVariable( 'DeleteGroupIDArray' ) : array();
$groupsInfo = array();
$deleteResult = array();
$deleteClassIDList = array();
foreach ( $deleteIDArray as $deleteID )
{
    $deletedClassName = '';
    $group = eZContentClassGroup::fetch( $deleteID );
    if ( $group != null )
    {
        $GroupName = $group->attribute( 'name' );
        $classList = eZContentClassClassGroup::fetchClassList( null, $deleteID );
        $groupClassesInfo = array();
        foreach ( $classList as $class )
        {
            $classID = $class->attribute( "id" );
            $classGroups = eZContentClassClassGroup::fetchGroupList( $classID, 0);
            if ( count( $classGroups ) == 1 )
            {
                $classObject = eZContentclass::fetch( $classID );
                $className = $classObject->attribute( "name" );
                $deletedClassName .= " '" . $className . "'" ;
                $deleteClassIDList[] = $classID;
                $groupClassesInfo[] = array( 'class_name'   => $className,
                                             'object_count' => $classObject->objectCount() );
            }
        }
        if ( $deletedClassName == '' )
            $deletedClassName = ezpI18n::tr( 'kernel/class', '(no classes)' );
        $deleteResult[] = array( 'groupName'        => $GroupName,
                                 'deletedClassName' => $deletedClassName );
        $groupsInfo[] = array( 'group_name' => $GroupName,
                               'class_list' => $groupClassesInfo );
    }
}
if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    foreach ( $deleteIDArray as $deleteID )
    {
        eZContentClassGroup::removeSelected( $deleteID );
        eZContentClassClassGroup::removeGroupMembers( $deleteID );
        foreach ( $deleteClassIDList as $deleteClassID )
        {
            $deleteClass = eZContentClass::fetch( $deleteClassID );
            if ( $deleteClass )
                $deleteClass->remove( true );
            $deleteClass = eZContentClass::fetch( $deleteClassID, true, eZContentClass::VERSION_STATUS_TEMPORARY );
            if ( $deleteClass )
                $deleteClass->remove( true );
        }
    }
    $Module->redirectTo( '/class/grouplist/' );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    $Module->redirectTo( '/class/grouplist/' );
}
$Module->setTitle( ezpI18n::tr( 'kernel/class', 'Remove class groups' ) . ' ' . $GroupName );
$tpl = eZTemplate::factory();

$tpl->setVariable( "DeleteResult", $deleteResult );
$tpl->setVariable( "module", $Module );
$tpl->setVariable( "groups_info", $groupsInfo );
$Result = array();
$Result['content'] = $tpl->fetch( "design:class/removegroup.tpl" );
$Result['path'] = array( array( 'url' => '/class/grouplist/',
                                'text' => ezpI18n::tr( 'kernel/class', 'Class groups' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/class', 'Remove class groups' ) ) );
?>
