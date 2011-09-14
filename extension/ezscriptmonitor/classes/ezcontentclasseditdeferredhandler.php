<?php
//
// Definition of eZContentClassEditDeferredHandler class
//
// Created on: <11-Jan-2010 11:56:00 pa>
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

/**
 * Handler for content class editing.
 */
class eZContentClassEditDeferredHandler
{

    /**
     * Create a scheduled script that will store the modification made to an eZContentClass.
     *
     * @param eZContentClass Content class to be stored.
     * @param array[eZContentClassAttribute] Attributes of the new content class.
     * @param array Unordered view parameters
     */
    public function store( eZContentClass $class, array $attributes, array &$unorderedParameters )
    {
        $script = eZScheduledScript::create( 'syncobjectattributes.php',
                                             eZINI::instance( 'ezscriptmonitor.ini' )->variable( 'GeneralSettings', 'PhpCliCommand' ) .
                                             ' extension/ezscriptmonitor/bin/' . eZScheduledScript::SCRIPT_NAME_STRING .
                                             ' -s ' . eZScheduledScript::SITE_ACCESS_STRING . ' --classid=' . $class->attribute( 'id' ) );
        $script->store();
        $unorderedParameters['ScheduledScriptID'] = $script->attribute( 'id' );
        $class->storeVersioned( $attributes, eZContentClass::VERSION_STATUS_MODIFIED );
    }
}

?>
