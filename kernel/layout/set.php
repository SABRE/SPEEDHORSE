<?php
//
// Created on: <09-Oct-2002 15:33:01 amos>
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

$LayoutStyle = $Params['LayoutStyle'];
$Module = $Params['Module'];

$userParamString = '';
foreach ( $Params['UserParameters'] as $key => $param )
{
    $userParamString .= "/($key)/$param";
}

$Result = array();
$Result['content'] = '';
$Result['rerun_uri'] = '/' . implode( '/', array_splice( $Params['Parameters'], 1 ) ) . $userParamString;

$layoutINI = eZINI::instance( 'layout.ini' );
$i18nINI = eZINI::instance( 'i18n.ini' );
if ( $layoutINI->hasGroup( $LayoutStyle ) )
{
    if ( $layoutINI->hasVariable( $LayoutStyle, 'PageLayout' ) )
        $Result['pagelayout'] = $layoutINI->variable( $LayoutStyle, 'PageLayout' );

    if ( $layoutINI->hasVariable( $LayoutStyle, 'ContentType' ) )
        header( 'Content-Type: ' . $layoutINI->variable( $LayoutStyle, 'ContentType' ) . '; charset=' . $i18nINI->variable( 'CharacterSettings', 'Charset' ) );

    $res = eZTemplateDesignResource::instance();
    $res->setKeys( array( array( 'layout', $LayoutStyle ) ) );

    if ( $layoutINI->hasVariable( $LayoutStyle, 'UseAccessPass' ) && $layoutINI->variable( $LayoutStyle, 'UseAccessPass' ) == 'false' )
    {
    }
    else
    {
        eZSys::addAccessPath( array( 'layout', 'set', $LayoutStyle ), 'layout', false );
    }



    $useFullUrl = false;
    $http = eZHTTPTool::instance();
    $http->UseFullUrl = false;
    if ( $layoutINI->hasVariable( $LayoutStyle, 'UseFullUrl' ) )
    {
        if ( $layoutINI->variable( $LayoutStyle, 'UseFullUrl' ) == 'true' )
        {
            $http->UseFullUrl = true;
        }
    }

    $Module->setExitStatus( eZModule::STATUS_RERUN );
}
else
{
    eZDebug::writeError( 'No such layout style: ' . $LayoutStyle, 'layout/set' );
}

?>
