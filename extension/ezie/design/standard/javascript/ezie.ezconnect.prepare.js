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

ezie.ezconnect.prepare = function (prepare_url) {
    ezie.ezconnect.connect.instance().reset();
    ezie.history().reset();
    $( "#ezieConnectionError" ).hide();

    ezie.ezconnect.connect.instance().prepare({
        'url': prepare_url,
        'success': function(response) {
            // We achieve setting the values for ezie.ezconnect
            ezie.ezconnect.connect.instance().set({
                'key': response.key,
                'image_id': response.image_id,
                'image_version': response.image_version,
                'history_version': response.history_version,
                'module_url': response.module_url
            });

            $.log('connexion preparation')

            ezie.gui.eziegui.getInstance().setImages(response.image_url, response.thumbnail_url);
            ezie.gui.config.zoom().init();
        },
        'complete': function() {
            ezie.gui.eziegui.getInstance().main().hideLoading();
        }
    });
};

