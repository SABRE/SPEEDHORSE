<?php
//
// Definition of eZMD5 class
//
// Created on: <04-Feb-2004 22:01:19 kk>
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

/*!
  \class eZMD5 ezmd5.php
  \brief Class handling MD5 file operations
*/

class eZMD5
{
    const CHECK_SUM_LIST_FILE = 'share/filelist.md5';

    /**
     * Check MD5 sum file to check if files have changed. Return array of changed files.
     *
     * @param string $file File name of md5 check sums file
     * @param string $subDirStr Sub dir where files in md5 check sum file resides
     *        e.g. '' (default) if root and 'extension/ezoe/' for ezoe extension.
     * @return array List of miss-matching files.
    */
    static function checkMD5Sums( $file, $subDirStr = '' )
    {
        $result = array();
        $lines  = file( $file, FILE_IGNORE_NEW_LINES );

        if ( $lines !== false && isset( $lines[0] ) )
        {
            foreach ( $lines as $key => $line )
            {
                if ( isset( $line[34] ) )
                {
                    $md5Key = substr( $line, 0, 32 );
                    $filename = $subDirStr . substr( $line, 34 );
                    if ( !file_exists( $filename ) || $md5Key != md5_file( $filename ) )
                    {
                        $result[] = $filename;
                    }
                }
            }
        }

        return $result;
    }
}
?>
