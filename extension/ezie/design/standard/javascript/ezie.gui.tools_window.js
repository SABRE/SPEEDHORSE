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

ezie.gui.tools_window = function() {
    var jWindow = null;
    var initialized = false;

    // returns the jQuery Dom element corresponding to
    // the window
    var getJWindow = function() {
        return jWindow;
    };

    var setBinds = function () {
        $.each(ezie.gui.config.bindings.tools_window, function() {
            var config = this;
            var item = $(config.selector);
            item.click(function () {
                if (! ezie.gui.eziegui.getInstance().isFrozen()) {
                    config.click();
                }
                return false;

            });

            if (config.shortcut) {
                item.attr("title", item.attr("title") + " (" + config.shortcut + ")");
                $(document).bind('keydown', config.shortcut, function (e) {
                    if (!ezie.gui.eziegui.getInstance().isFrozen()) {
                        config.click();
                        e.stopPropagation( );
                        e.preventDefault( );
                    }

                    return false;
                } );
            }

            if (item.attr('title') != undefined) {
                var p = item.closest('div.ezieBox').find('div.bottomBarContent p')
                var oldcontent = p.html()

                item.hover(function (){
                    p.html($(this).attr('title'))
                }, function () {
                    p.html(oldcontent)
                });
            }

        })

    };

    var init = function() {
        setBinds();
        jWindow = $("#ezieToolsWindow");
        initialized = true;
    };

    var freeze = function() {
        $(".filters").add(".tools").freeze();
    }
    var unfreeze = function() {
        $(".filters").add(".tools").unfreeze();
    }

    var hide = function () {
        jWindow.fadeOut('fast');
    };

    var show = function () {
        if (!initialized) {
            init();
        }
        jWindow.fadeIn('fast');
    }

    return {
        jWindow:getJWindow,
        show:show,
        hide:hide,
        freeze:freeze,
        unfreeze:unfreeze
    };
};
