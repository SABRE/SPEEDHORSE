<?php
//
// Created on: <24-Apr-2002 11:18:59 bf>
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



$http = eZHTTPTool::instance();

$tpl = eZTemplate::factory();

$ViewMode = $Params['ViewMode'];
$NodeID = $Params['NodeID'];
$Module = $Params['Module'];
$LanguageCode = $Params['Language'];
$Offset = $Params['Offset'];
$Year = $Params['Year'];
$Month = $Params['Month'];
$Day = $Params['Day'];

// Check if we should switch access mode (http/https) for this node.
eZSSLZone::checkNodeID( 'content', 'view', $NodeID );

if ( isset( $Params['UserParameters'] ) )
{
    $UserParameters = $Params['UserParameters'];
}
else
{
    $UserParameters = array();
}

if ( $Offset )
    $Offset = (int) $Offset;
if ( $Year )
    $Year = (int) $Year;
if ( $Month )
    $Month = (int) $Month;
if ( $Day )
    $Day = (int) $Day;

$NodeID = (int) $NodeID;

if ( $NodeID < 2 )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$ini = eZINI::instance();
$viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );

if ( isset( $Params['ViewCache'] ) )
{
    $viewCacheEnabled = $Params['ViewCache'];
}
elseif ( $viewCacheEnabled && !in_array( $ViewMode, $ini->variableArray( 'ContentSettings', 'CachedViewModes' ) ) )
{
    $viewCacheEnabled = false;
}

if ( $viewCacheEnabled && $ini->hasVariable( 'ContentSettings', 'ViewCacheTweaks' ) )
{
    $viewCacheTweaks = $ini->variable( 'ContentSettings', 'ViewCacheTweaks' );
    if ( isset( $viewCacheTweaks[$NodeID] ) && strpos( $viewCacheTweaks[$NodeID], 'disabled' ) !== false )
    {
        $viewCacheEnabled = false;
    }
}

$collectionAttributes = false;
if ( isset( $Params['CollectionAttributes'] ) )
    $collectionAttributes = $Params['CollectionAttributes'];

$validation = array( 'processed' => false,
                     'attributes' => array() );
if ( isset( $Params['AttributeValidation'] ) )
    $validation = $Params['AttributeValidation'];

$res = eZTemplateDesignResource::instance();
$keys = $res->keys();
if ( isset( $keys['layout'] ) )
    $layout = $keys['layout'];
else
    $layout = false;

$viewParameters = array( 'offset' => $Offset,
                         'year' => $Year,
                         'month' => $Month,
                         'day' => $Day,
                         'namefilter' => false );
$viewParameters = array_merge( $viewParameters, $UserParameters );

$user = eZUser::currentUser();

eZDebugSetting::addTimingPoint( 'kernel-content-view', 'Operation start' );


$operationResult = array();

if ( eZOperationHandler::operationIsAvailable( 'content_read' ) )
{
    $operationResult = eZOperationHandler::execute( 'content', 'read', array( 'node_id' => $NodeID,
                                                                              'user_id' => $user->id(),
                                                                              'language_code' => $LanguageCode ), null, true );
}

if ( ( isset( $operationResult['status'] ) && $operationResult['status'] != eZModuleOperationInfo::STATUS_CONTINUE ) )
{
    switch( $operationResult['status'] )
    {
        case eZModuleOperationInfo::STATUS_HALTED:
        case eZModuleOperationInfo::STATUS_REPEAT:
        {
            if ( isset( $operationResult['redirect_url'] ) )
            {
                $Module->redirectTo( $operationResult['redirect_url'] );
                return;
            }
            else if ( isset( $operationResult['result'] ) )
            {
                $result = $operationResult['result'];
                $resultContent = false;
                if ( is_array( $result ) )
                {
                    if ( isset( $result['content'] ) )
                    {
                        $resultContent = $result['content'];
                    }
                    if ( isset( $result['path'] ) )
                    {
                        $Result['path'] = $result['path'];
                    }
                }
                else
                {
                    $resultContent = $result;
                }
                $Result['content'] = $resultContent;
            }
        } break;
        case eZModuleOperationInfo::STATUS_CANCELLED:
        {
            $Result = array();
            $Result['content'] = "Content view cancelled<br/>";
        } break;
    }
    return $Result;
}
else
{
    $localVars = array( "cacheFileArray", "NodeID",   "Module", "tpl",
                        "LanguageCode",   "ViewMode", "Offset", "ini",
                        "cacheFileArray", "viewParameters",  "collectionAttributes",
                        "validation" );
    if ( $viewCacheEnabled )
    {
        $user = eZUser::currentUser();

        $cacheFileArray = eZNodeviewfunctions::generateViewCacheFile( $user, $NodeID, $Offset, $layout, $LanguageCode, $ViewMode, $viewParameters, false );

        $cacheFilePath = $cacheFileArray['cache_path'];

        $cacheFile = eZClusterFileHandler::instance( $cacheFilePath );
        $args = compact( $localVars );
        $Result = $cacheFile->processCache( array( 'eZNodeviewfunctions', 'contentViewRetrieve' ),
                                            array( 'eZNodeviewfunctions', 'contentViewGenerate' ),
                                            null,
                                            null,
                                            $args );
        return $Result;
    }
    else
    {
        $cacheFileArray = array( 'cache_dir' => false, 'cache_path' => false );
        $args = compact( $localVars );
        $data = eZNodeviewfunctions::contentViewGenerate( false, $args ); // the false parameter will disable generation of the 'binarydata' entry
        return $data['content']; // Return the $Result array
    }
}

// Looking for some view-cache code?
// Try the eZNodeviewfunctions class for enlightenment.
?>
