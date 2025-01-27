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

ezie.gui.config.bind.menu_close_without_saving = function() {
    if (!ezie.gui.eziegui.isInstanciated()) { // TODO: also when the mainwindow is not open/visible
        return;
    }
    
    // TODO: call this when the user leaves the page (ie, fx et chrome)
    if (!confirm('If you leave without saving, all your modifications will be definitely lost')) {
        return;
    }

    $.log('starting quit + no save');

    ezie.gui.eziegui.getInstance().desactivateUndo();
    ezie.gui.eziegui.getInstance().desactivateRedo();

    ezie.ezconnect.connect.instance().action({
        'action': 'no_save_and_quit',
        'success': function() {
            ezie.gui.eziegui.getInstance().close();
            // update the frontend
            $('#ezieToolsWindow').find('.current').removeClass('current');
            $('#ezie_zoom').parent().addClass('current');
        }
    });

}
