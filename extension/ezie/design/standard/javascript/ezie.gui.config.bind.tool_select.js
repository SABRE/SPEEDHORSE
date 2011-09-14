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

ezie.gui.config.bind.tool_select_api = null;
ezie.gui.config.select_custom_opts = null;
ezie.gui.config.bind.select_method_has_changed = true;
ezie.gui.config.bind.select_last_was_wm = false;

ezie.gui.config.bind.tool_select = function(selection, options) {
    ezie.gui.eziegui.getInstance().opts().showOpts("#optsSelect");

    ezie.gui.config.bind.set_tool_select(selection, options, false);
}

ezie.gui.config.bind.set_tool_select = function(selection, options, wm) {
    ezie.gui.config.bind.select_method_has_changed = true;

    var settings = {
        onSelect: ezie.gui.selection().set,
        onChange: ezie.gui.selection().set
    };

    if (typeof options != "undefined") {
        ezie.gui.config.select_custom_opts = options;
        $.extend(settings, options);
    } else {
        ezie.gui.config.select_custom_opts = null;
    }

    if (ezie.gui.config.bind.tool_select_api != null) {
        if (((!wm && !ezie.gui.config.bind.select_last_was_wm) || (ezie.gui.config.bind.select_last_was_wm && wm)) && selection) {
            ezie.gui.config.bind.tool_select_api.setSelect([selection.x, selection.y,
                selection.x + selection.w, selection.y + selection.h])
            ezie.gui.selection().set(selection);
            ezie.gui.config.bind.select_last_was_wm = wm;
            return;
        }

    }

    if (!wm && $('#optsSelect input[type="radio"]:checked:first').val() == 'ratio') {
        var selectWidth = $('#optsSelect input[type="text"][name="selection_width"]:first').val();
        var selectHeight = $('#optsSelect input[type="text"][name="selection_height"]:first').val();

        settings.aspectRatio = selectWidth / selectHeight;
    }

    if (ezie.gui.config.bind.tool_select_api != null)
        ezie.gui.config.bind.tool_select_api.destroy();
    ezie.gui.config.bind.tool_select_api  = $.Jcrop("#main_image img:first", settings);

    if (selection != null) {
        ezie.gui.config.bind.tool_select_api.setSelect([selection.x, selection.y,
            selection.x + selection.w, selection.y + selection.h])
        ezie.gui.selection().set(selection);
    }

    ezie.gui.config.bind.select_last_was_wm = wm;
}

ezie.gui.config.bind.tool_select_remove = function (){
    if (ezie.gui.config.bind.tool_select_api  != null) {
        ezie.gui.config.bind.tool_select_api.destroy();
        ezie.gui.selection().deactivate();
        $.log('on unset custop opts-1');
        ezie.gui.config.select_custom_opts = null;
        ezie.gui.config.bind.tool_select_api = null;
    }
}

ezie.gui.config.bind.tool_select_method = function() {

    var selectMethod = $('#optsSelect input[type="radio"]:checked:first').val();
    var selectWidth = $('#optsSelect input[type="text"][name="selection_width"]:first').val();
    var selectHeight = $('#optsSelect input[type="text"][name="selection_height"]:first').val();

    var settings = {
        onSelect: ezie.gui.selection().set,
        onChange: ezie.gui.selection().set
    };
    var dims = null;

    $.log('selection method: ' + selectMethod);
    $.log('   w: ' + selectWidth);
    $.log('   h: ' + selectHeight);

    if (ezie.gui.selection().isSelectionActive()) {
        dims = ezie.gui.selection().selection();
        settings.setSelect = [dims.x, dims.y, dims.x + dims.w, dims.y + dims.h];
    } else {
        settings.setSelect = [0, 0, selectWidth, selectHeight];
    }

    switch(selectMethod) {
        case 'ratio':
            if (selectHeight == 0) {
                selectHeight = 1;
            }

            settings.aspectRatio = selectWidth / selectHeight;

            settings.setSelect[3] = settings.setSelect[3] * settings.aspectRatio;

            break;
        case 'free':
            settings.aspectRatio = null;
            $.log('on entre dans free');
            break;
    }

    if( ezie.gui.config.bind.tool_select_api != null ) {
        ezie.gui.config.bind.tool_select_api.setOptions( { aspectRatio: settings.aspectRatio } );
        ezie.gui.config.bind.tool_select_api.setSelect( settings.setSelect );
    }else{
        ezie.gui.config.bind.tool_select_api  = $.Jcrop("#main_image img:first", settings);
    }

    // hack to avoid an eZ Publish function I can't find that blocks
    // the changes of values of the input radios
    // Throws anything
    throw "this looks like an error but it's not :)";

    return false;
}
