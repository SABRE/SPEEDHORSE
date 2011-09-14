<?php
//
// Created on: <08-Jan-2003 16:36:23 amos>
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
$class = eZContentClass::fetch( $ClassID, true, 0 );
if ( !$class )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE );

$classCopy = clone $class;
$classCopy->initializeCopy( $class );
$classCopy->setAttribute( 'version', eZContentClass::VERSION_STATUS_MODIFIED );
$classCopy->store();

$mainGroupID = false;
$classGroups = eZContentClassClassGroup::fetchGroupList( $class->attribute( 'id' ),
                                                          $class->attribute( 'version' ) );
for ( $i = 0; $i < count( $classGroups ); ++$i )
{
    $classGroup =& $classGroups[$i];
    $classGroup->setAttribute( 'contentclass_id', $classCopy->attribute( 'id' ) );
    $classGroup->setAttribute( 'contentclass_version', $classCopy->attribute( 'version' ) );
    $classGroup->store();
    if ( $mainGroupID === false )
        $mainGroupID = $classGroup->attribute( 'group_id' );
}

$classAttributeCopies = array();
$classAttributes = $class->fetchAttributes();
foreach ( array_keys( $classAttributes ) as $classAttributeKey )
{
    $classAttribute =& $classAttributes[$classAttributeKey];
    $classAttributeCopy = clone $classAttribute;

    if ( $datatype = $classAttributeCopy->dataType() ) //avoiding fatal error if datatype not exist (was removed).
    {
        $datatype->cloneClassAttribute( $classAttribute, $classAttributeCopy );
    }
    else
    {
        continue;
    }

    $classAttributeCopy->setAttribute( 'contentclass_id', $classCopy->attribute( 'id' ) );
    $classAttributeCopy->setAttribute( 'version', eZContentClass::VERSION_STATUS_MODIFIED );
    $classAttributeCopy->store();
    $classAttributeCopies[] =& $classAttributeCopy;
    unset( $classAttributeCopy );
}

$ini = eZINI::instance( 'content.ini' );
$classRedirect = strtolower( trim( $ini->variable( 'CopySettings', 'ClassRedirect' ) ) );

switch ( $classRedirect )
{
    case 'grouplist':
    {
        $classCopy->storeDefined( $classAttributeCopies );
        return $Module->redirectToView( 'grouplist', array() );
    } break;

    case 'classlist':
    {
        $classCopy->storeDefined( $classAttributeCopies );
        return $Module->redirectToView( 'classlist', array( $mainGroupID ) );
    } break;

    case 'classview':
    {
        $classCopy->storeDefined( $classAttributeCopies );
        return $Module->redirectToView( 'view', array( $classCopy->attribute( 'id' ) ) );
    } break;

    default:
    {
        eZDebug::writeWarning( "Invalid ClassRedirect value '$classRedirect', use one of: grouplist, classlist, classedit or classview" );
    }

    case 'classedit':
    {
        return $Module->redirectToView( 'edit', array( $classCopy->attribute( 'id' ) ) );
    } break;
}

?>
