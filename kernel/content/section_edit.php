<?php
//
// Created on: <18-Aug-2006 12:46:05 vd>
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

function sectionEditPostFetch( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage, &$validation )
{
}

function sectionEditPreCommit( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage )
{
}

function sectionEditActionCheck( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage )
{
    if ( $module->isCurrentAction( 'SectionEdit' ) )
    {
        $http = eZHTTPTool::instance();
        if ( $http->hasPostVariable( 'SelectedSectionId' ) )
        {
            $selectedSectionID = (int) $http->postVariable( 'SelectedSectionId' );
            $selectedSection = eZSection::fetch( $selectedSectionID );
            if ( is_object( $selectedSection ) )
            {
                $currentUser = eZUser::currentUser();
                if ( $currentUser->canAssignSectionToObject( $selectedSectionID, $object ) )
                {
                    $db = eZDB::instance();
                    $db->begin();
                    $assignedNodes = $object->attribute( 'assigned_nodes' );
                    if ( count( $assignedNodes ) > 0 )
                    {
                        foreach ( $assignedNodes as $node )
                        {
                            if ( eZOperationHandler::operationIsAvailable( 'content_updatesection' ) )
                            {
                                $operationResult = eZOperationHandler::execute( 'content',
                                                                                'updatesection',
                                                                                array( 'node_id'             => $node->attribute( 'node_id' ),
                                                                                       'selected_section_id' => $selectedSectionID ),
                                                                                null,
                                                                                true );

                            }
                            else
                            {
                                eZContentOperationCollection::updateSection( $node->attribute( 'node_id' ), $selectedSectionID );
                            }
                        }
                    }
                    else
                    {
                        // If there are no assigned nodes we should update db for the current object.
                        $objectID = $object->attribute( 'id' );
                        $db->query( "UPDATE ezcontentobject SET section_id='$selectedSectionID' WHERE id = '$objectID'" );
                        $db->query( "UPDATE ezsearch_object_word_link SET section_id='$selectedSectionID' WHERE  contentobject_id = '$objectID'" );
                    }
                    $object->expireAllViewCache();
                    $db->commit();
                }
                else
                {
                    eZDebug::writeError( "You do not have permissions to assign the section <" . $selectedSection->attribute( 'name' ) .
                                         "> to the object <" . $object->attribute( 'name' ) . ">." );
                }
                $module->redirectToView( 'edit', array( $object->attribute( 'id' ), $editVersion, $editLanguage, $fromLanguage ) );
            }
        }
    }
}

function sectionEditPreTemplate( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $tpl )
{
}

function initializeSectionEdit( $module )
{
    $module->addHook( 'post_fetch', 'sectionEditPostFetch' );
    $module->addHook( 'pre_commit', 'sectionEditPreCommit' );
    $module->addHook( 'action_check', 'sectionEditActionCheck' );
    $module->addHook( 'pre_template', 'sectionEditPreTemplate' );
}

?>
