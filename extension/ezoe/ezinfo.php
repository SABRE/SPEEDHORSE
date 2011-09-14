<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor
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

class ezoeInfo
{
    static function info()
    {
        return array( 'Name'      => '<a href="http://projects.ez.no/ezoe">eZ Online Editor</a> extension',
                      'Version'   => '5.3.0',
                      'Copyright' => 'Copyright (C) 1999-2011 eZ Systems AS',
                      'License'   => 'eZ Proprietary Use License v1.0',
                      'Includes the following third-party software' => array( 'Name' => 'Ephox Enterprise TinyMCE Javascript HTML WYSIWYG editor',
                                                                              'Version' => '3.3.9.3-328',
                                                                              'Copyright' => 'Copyright (C) 2010, Ephox Corporation, All rights reserved.',
                                                                              'License' => 'GNU Lesser General Public License v2.1',),
                      'Includes the following third-party icons'    => array( 'Name' => 'Tango Icon theme',
                                                                              'Version' => '0.8.90',
                                                                              'Copyright' => 'Copyright (C) 1999-2010 http://tango.freedesktop.org/Tango_Icon_Library',
                                                                              'License' => 'Creative Commons Attribution-ShareAlike 2.5',)
                    );
    }
}

?>
