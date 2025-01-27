// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Image Editor extension for eZ Publish
// SOFTWARE RELEASE: 1.2.0
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

ezie.gui.config.bind.filter_contrast = function() {
    $.log('starting contrast filter');

    ezie.gui.eziegui.getInstance().opts().showOpts("#optsContrast");
}

ezie.gui.config.bind.filter_contrast_slide = function(val) {
    $('#optsContrast input[name="optsContrastValue"]:first').val(val);
}

ezie.gui.config.bind.filter_contrast_submit = function() {
    var val = $('#optsContrast input[name="optsContrastValue"]:first').val();

    $.log('sending contrast action');

    ezie.ezconnect.connect.instance().action({
        'action':   'filter_contrast',
        'data':     {
            'value':    val
        }
    });
}
