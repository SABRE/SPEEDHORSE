<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore
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

class ezjscoreInfo
{
    static function info()
    {
        $eZCopyrightString = 'Copyright (C) 1999-2011 eZ Systems AS';

        return array( 'Name'      => '<a href="http://projects.ez.no/ezjscore">eZ JSCore</a> extension',
                      'Version'   => '1.3.0',
                      'Copyright' => $eZCopyrightString,
                      'License'   => 'eZ Proprietary Use License v1.0',
                      'Includes the following third-party software' => array( 'Name' => 'YUI',
                                                                              'Version' => "3.3.0 and 2.8.1",
                                                                              'Copyright' => 'Copyright (c) 2010, Yahoo! Inc. All rights reserved.',
                                                                              'License' => 'Licensed under the BSD License',),
                      'Includes the following third-party software (2)' => array( 'Name' => 'jQuery',
                                                                              'Version' => "1.4.3",
                                                                              'Copyright' => 'Copyright (c) 2009 John Resig',
                                                                              'License' => 'Dual licensed under the MIT and GPL licenses',),
                      'Includes the following third-party software (3)' => array( 'Name' => 'JSMin (jsmin-php)',
                                                                              'Version' => "1.1.1",
                                                                              'Copyright' => '2002 Douglas Crockford <douglas@crockford.com> (jsmin.c), 2008 Ryan Grove <ryan@wonko.com> (PHP port)',
                                                                              'License' => 'Licensed under the MIT License',),
                    );
    }
}

?>
