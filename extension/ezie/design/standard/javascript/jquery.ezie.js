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

(function($) {
    $.log = function(msg) {
        if ( window.console !== undefined )
            console.log( msg );
    }

    $.fn.ezie = function() {
        this.each(function() {
            $(this).click(function() {
                var url = $(this).attr('name');

                if (url.indexOf('ezieEdit[') != 0) {
                    return;
                }

                url = url.substring(9, url.lastIndexOf(']'));
                e = ezie.gui.eziegui.getInstance();
                // opening ui with the url to call to prepare the image to be
                // edited
                e.open(url, this);
            });
        });
        return this;
    };

    $.fn.freeze = function (opacity) {
        var params = $.extend({
            opacity:0.6
        },
        opacity
        );
        function freeze(j) {
            j.css("opacity", params.opacity);
        }
        return this.each(function() {
            freeze($(this));
        })
    }

    $.fn.unfreeze = function (opacity) {
        var params = $.extend({
            opacity:1
        },
        opacity
        );
        function unfreeze(j) {
            j.css("opacity", params.opacity);
        }
        return this.each(function() {
            unfreeze($(this));
        })
    }

})(jQuery);
