<?php
//
// Created on: <20-mar-2005 13:37:23 jk>
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

$module = $Params['Module'];

if ( !$module->hasActionParameter( 'NodeID' ) )
{
    eZDebug::writeError( 'Missing NodeID parameter for action ' . $module->currentAction(),
                         'content/translation' );
    return $module->redirectToView( 'view', array( 'full', 2 ) );
}

$nodeID = $module->actionParameter( 'NodeID' );

if ( !$module->hasActionParameter( 'LanguageCode' ) )
{
    eZDebug::writeError( 'Missing LanguageCode parameter for action ' . $module->currentAction(),
                         'content/translation' );
    return $module->redirectToView( 'view', array( 'full', 2 ) );
}

$languageCode = $module->actionParameter( 'LanguageCode' );

$viewMode = 'full';
if ( !$module->hasActionParameter( 'ViewMode' ) )
{
    $viewMode = $module->actionParameter( 'ViewMode' );
}

if ( $module->isCurrentAction( 'Cancel' ) )
{
    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}

if ( !$module->hasActionParameter( 'ObjectID' ) )
{
    eZDebug::writeError( 'Missing ObjectID parameter for action ' . $module->currentAction(),
                         'content/translation' );
    return $module->redirectToView( 'view', array( 'full', 2 ) );
}
$objectID = $module->actionParameter( 'ObjectID' );


$object = eZContentObject::fetch( $objectID );

if ( !$object )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $module->isCurrentAction( 'UpdateInitialLanguage' ) )
{
    if ( $module->hasActionParameter( 'InitialLanguageID' ) )
    {
        $newInitialLanguageID = $module->actionParameter( 'InitialLanguageID' );

        if ( !$object->canEdit() )
        {
            return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );
        }

        if ( eZOperationHandler::operationIsAvailable( 'content_updateinitiallanguage' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content', 'updateinitiallanguage',
                                                            array( 'object_id'               => $objectID,
                                                                   'new_initial_language_id' => $newInitialLanguageID,
                                                                   // note : the $nodeID parameter is ignored here but is
                                                                   // provided for events that need it
                                                                   'node_id'                 => $nodeID ) );
        }
        else
        {
            eZContentOperationCollection::updateInitialLanguage( $objectID, $newInitialLanguageID );
        }
    }

    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'UpdateAlwaysAvailable' ) )
{
    if ( !$object->canEdit() )
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel', array() );
    }

    $newAlwaysAvailable = $module->hasActionParameter( 'AlwaysAvailable' );

    if ( eZOperationHandler::operationIsAvailable( 'content_updatealwaysavailable' ) )
    {
        $operationResult = eZOperationHandler::execute( 'content', 'updatealwaysavailable',
                                                        array( 'object_id'            => $objectID,
                                                               'new_always_available' => $newAlwaysAvailable,
                                                               // note : the $nodeID parameter is ignored here but is
                                                               // provided for events that need it
                                                               'node_id'              => $nodeID ) );
    }
    else
    {
        eZContentOperationCollection::updateAlwaysAvailable( $objectID, $newAlwaysAvailable );
    }

    return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
}
else if ( $module->isCurrentAction( 'RemoveTranslation' ) )
{
    if ( !$module->hasActionParameter( 'LanguageID' ) )
    {
        return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
    }

    $languageIDArray = $module->actionParameter( 'LanguageID' );

    if ( $module->hasActionParameter( 'ConfirmRemoval' ) && $module->actionParameter( 'ConfirmRemoval' ) )
    {
        if ( eZOperationHandler::operationIsAvailable( 'content_removetranslation' ) )
        {
            $operationResult = eZOperationHandler::execute( 'content', 'removetranslation',
                                                            array( 'object_id'        => $objectID,
                                                                   'language_id_list' => $languageIDArray,
                                                                   // note : the $nodeID parameter is ignored here but is
                                                                   // provided for events that need it
                                                                   'node_id'          => $nodeID ) );

        }
        else
        {
            eZContentOperationCollection::removeTranslation( $objectID, $languageIDArray );
        }

        return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
    }

    $languages = array();
    foreach( $languageIDArray as $languageID )
    {
        $language = eZContentLanguage::fetch( $languageID );
        if ( $language )
        {
            $languages[] = $language;
        }
    }

    if ( !$languages )
    {
        return $module->redirectToView( 'view', array( $viewMode, $nodeID, $languageCode ) );
    }

    $tpl = eZTemplate::factory();

    $tpl->setVariable( 'object_id', $objectID );
    $tpl->setVariable( 'object', $object );
    $tpl->setVariable( 'node_id', $nodeID );
    $tpl->setVariable( 'language_code', $languageCode );
    $tpl->setVariable( 'languages', $languages );
    $tpl->setVariable( 'view_mode', $viewMode );

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:content/removetranslation.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezpI18n::tr( 'kernel/content', 'Remove translation' ) ) );

    return;
}

return $module->redirectToView( 'view', array( 'full', 2 ) );

?>
