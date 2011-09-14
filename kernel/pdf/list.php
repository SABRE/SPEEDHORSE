<?php
//
// eZSetup - init part initialization
//
// Created on: <29-Oct-2003 14:49:54 kk>
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


// Create new PDF Export
if ( $Module->isCurrentAction( 'NewExport' ) )
{
    return $Module->redirect( 'pdf', 'edit' );
}
//Remove existing PDF Export(s)
else if ( $Module->isCurrentAction( 'RemoveExport' ) && $Module->hasActionParameter( 'DeleteIDArray' ) )
{
    $deleteArray = $Module->actionParameter( 'DeleteIDArray' );
    foreach ( $deleteArray as $deleteID )
    {
        // remove draft if it exists:
        $pdfExport = eZPDFExport::fetch( $deleteID, true, eZPDFExport::VERSION_DRAFT );
        if ( $pdfExport )
        {
            $pdfExport->remove();
        }
        // remove default version:
        $pdfExport = eZPDFExport::fetch( $deleteID );
        if ( $pdfExport )
        {
            $pdfExport->remove();
        }
    }
}

$exportArray = eZPDFExport::fetchList();
$exportList = array();
foreach( $exportArray as $export )
{
    $exportList[$export->attribute( 'id' )] = $export;
}

$tpl = eZTemplate::factory();

$tpl->setVariable( 'pdfexport_list', $exportList );

$Result = array();
$Result['content'] = $tpl->fetch( "design:pdf/list.tpl" );
$Result['path'] = array( array( 'url' => 'kernel/pdf',
                                'text' => ezpI18n::tr( 'kernel/pdf', 'PDF Export' ) ) );

?>
