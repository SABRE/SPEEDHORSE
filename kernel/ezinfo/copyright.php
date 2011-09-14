<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
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

$Module = $Params['Module'];

$text =
## BEGIN COPYRIGHT INFO ##
'<p>Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.</p>

<p>This file may be distributed and/or modified under the terms of the
\"GNU General Public License\" version 2 as published by the Free
Software Foundation and appearing in the file LICENSE included in
the packaging of this file.</p>

<p>Licencees holding a valid \"eZ Publish professional licence\" version 2
may use this file in accordance with the \"eZ Publish professional licence\"
version 2 Agreement provided with the Software.</p>

<p>This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE.</p>

<p>The \"eZ Publish professional licence\" version 2 is available at
<a href=\"http://ez.no/ez_publish/licences/professional/\">http://ez.no/ez_publish/licences/professional/</a> and in the file
PROFESSIONAL_LICENCE included in the packaging of this file.
For pricing of this licence please contact us via e-mail to licence@ez.no.
Further contact information is available at <a href=\"http://ez.no/company/contact/\">http://ez.no/company/contact/</a>.</p>

<p>The \"GNU General Public License\" (GPL) is available at
<a href=\"http://www.gnu.org/copyleft/gpl.html\">http://www.gnu.org/copyleft/gpl.html</a>.</p>

<p>Contact eZ Systems if any conditions of this licencing isn\'t clear to you.</p>';
## END COPYRIGHT INFO ##

$Result = array();
$Result['content'] = $text;
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/ezinfo', 'Info' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/ezinfo', 'Copyright' ) ) );

?>
