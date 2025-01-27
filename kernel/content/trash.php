<?php
//
// Definition of Trash class
//
// Created on: <28-Jan-2003 13:19:47 sp>
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

/*! \file
*/


$Module = $Params['Module'];
$Offset = $Params['Offset'];
if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}
$viewParameters = array( 'offset' => $Offset, 'namefilter' => false );
$viewParameters = array_merge( $viewParameters, $UserParameters );

$http = eZHTTPTool::instance();

$user = eZUser::currentUser();
$userID = $user->id();

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $access = $user->hasAccessTo( 'content', 'cleantrash' );
        if ( $access['accessWord'] == 'yes' || $access['accessWord'] == 'limited' )
        {
            $deleteIDArray = $http->postVariable( 'DeleteIDArray' );

            foreach ( $deleteIDArray as $deleteID )
            {

                $objectList = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                                   null,
                                                                   array( 'id' => $deleteID ),
                                                                   null,
                                                                   null,
                                                                   true );
                foreach ( $objectList as $object )
                {
                    $object->purge();
                }
            }
        }
        else
        {
            return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
        }
    }
}
else if ( $http->hasPostVariable( 'EmptyButton' )  )
{
    $access = $user->hasAccessTo( 'content', 'cleantrash' );
    if ( $access['accessWord'] == 'yes' || $access['accessWord'] == 'limited' )
    {
        while ( true )
        {
            // Fetch 100 objects at a time, to limit transaction size
            $objectList = eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                               null,
                                                               array( 'status' => eZContentObject::STATUS_ARCHIVED ),
                                                               null,
                                                               100,
                                                               true );
            if ( count( $objectList ) < 1 )
                break;

            foreach ( $objectList as $object )
            {
                $object->purge();
            }
        }
    }
    else
    {
        return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

$tpl = eZTemplate::factory();
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/trash.tpl' );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/content', 'Trash' ),
                                'url' => false ) );


?>
