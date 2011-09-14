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

function stateEditPostFetch( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage, &$validation )
{
}

function stateEditPreCommit( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage )
{
}

function stateEditActionCheck( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage )
{
    if ( $module->isCurrentAction( 'StateEdit' ) )
    {
        $http = eZHTTPTool::instance();
        if ( $http->hasPostVariable( 'SelectedStateIDList' ) )
        {
            $selectedStateIDList = $http->postVariable( 'SelectedStateIDList' );
            $objectID = $object->attribute( 'id' );

            if ( eZOperationHandler::operationIsAvailable( 'content_updateobjectstate' ) )
            {
                $operationResult = eZOperationHandler::execute( 'content', 'updateobjectstate',
                                                                array( 'object_id'     => $objectID,
                                                                       'state_id_list' => $selectedStateIDList ) );
            }
            else
            {
                eZContentOperationCollection::updateObjectState( $objectID, $selectedStateIDList );
            }
            $module->redirectToView( 'edit', array( $object->attribute( 'id' ), $editVersion, $editLanguage, $fromLanguage ) );
        }
    }
}

function stateEditPreTemplate( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $tpl )
{
}

function initializeStateEdit( $module )
{
    $module->addHook( 'post_fetch', 'stateEditPostFetch' );
    $module->addHook( 'pre_commit', 'stateEditPreCommit' );
    $module->addHook( 'action_check', 'stateEditActionCheck' );
    $module->addHook( 'pre_template', 'stateEditPreTemplate' );
}

?>
