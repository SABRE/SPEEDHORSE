<?php
//
// Definition of eZOverride class
//
// Created on: <31-Oct-2002 09:18:07 amos>
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
 * Not currently used for anything
 * @deprecated 4.5
 */
class eZOverride
{
    static function selectFile( $matches, $matchKeys, &$matchedKeys, $regexpMatch )
    {
        $match = null;
        foreach ( $matches as $templateMatch )
        {
            $templatePath = $templateMatch["file"];
            $templateType = $templateMatch["type"];
            if ( $templateType == "normal" )
            {
                if ( file_exists( $templatePath ) )
                {
                    $match = $templateMatch;
                    break;
                }
            }
            else if ( $templateType == "override" )
            {
                $foundOverrideFile = false;
                if ( file_exists( $templatePath ) )
                {
                    $match = $templateMatch;
                    $match["file"] = $templatePath;
                    $foundOverrideFile = true;
                }
                if ( !$foundOverrideFile and
                     count( $matchKeys ) == 0 )
                    continue;
                if ( !$foundOverrideFile and
                     preg_match( $regexpMatch, $templatePath, $regs ) )// Check for dir/filebase_keyname_keyid.tpl, eg. content/view_section_1.tpl
                {
                    foreach ( $matchKeys as $matchKeyName => $matchKeyValue )
                    {
                        $file = $regs[1] . "/" . $regs[2] . "_$matchKeyName" . "_$matchKeyValue" . $regs[3];
                        if ( file_exists( $file ) )
                        {
                            $match = $templateMatch;
                            $match["file"] = $file;
                            $foundOverrideFile = true;
                            $matchedKeys[$matchKeyName] = $matchKeyValue;
                            break;
                        }
                    }
                }
                if ( $match !== null )
                    break;
            }
        }
        return $match;
    }
}

?>
