<?php
//
// Definition of eZURLAliasFilter class
//
// Created on: <05-Oct-2007 09:03:31 jr>
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
 * This class is an URL alias filter to be run with the eZURLAliasFilter system
 *
 * It appends the value of the nodeID in the URL so the contents can be for
 * example indexed by Google for its Google News
 */
class eZURLAliasFilterAppendNodeID extends eZURLAliasFilter
{
    /**
     * Empty constructor
     */
    public function __construct() {}

    /**
     * Append the node ID of the object being published
     * So its URL alias will look like :
     * someurlalias-<nodeID>
     *
     * @param string The text of the URL alias
     * @param object The eZContentObject object being published
     * @params object The eZContentObjectTreeNode in which the eZContentObject is published
     * @return string The transformed URL alias with the nodeID
     */
    public function process( $text, &$languageObject, &$caller )
    {
        if( !$caller instanceof eZContentObjectTreeNode )
        {
            eZDebug::writeError( 'The caller variable was not an eZContentObjectTreeNode', __METHOD__ );
            return $text;
        }

        $ini = eZINI::instance( 'site.ini' );
        $applyOnClassList = $ini->variable( 'AppendNodeIDFilterSettings', 'ApplyOnClass' );

        $classIdentifier = $caller->attribute( 'class_identifier' );

        if( in_array( $classIdentifier, $applyOnClassList ) )
        {
            $separator  = eZCharTransform::wordSeparator();
            $text .= $separator . $caller->attribute( 'node_id' );
        }

        return $text;
    }
}
?>
