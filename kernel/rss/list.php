<?php
//
// eZSetup - init part initialization
//
// Created on: <17-Sep-2003 11:00:54 kk>
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


$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( 'NewExportButton' ) )
{
    return $Module->run( 'edit_export', array() );
}
else if ( $http->hasPostVariable( 'RemoveExportButton' ) && $http->hasPostVariable( 'DeleteIDArray' ) )
{
    $deleteArray = $http->postVariable( 'DeleteIDArray' );
    foreach ( $deleteArray as $deleteID )
    {
        $rssExport = eZRSSExport::fetch( $deleteID, true, eZRSSExport::STATUS_DRAFT );
        if ( $rssExport )
        {
            $rssExport->remove();
        }
        $rssExport = eZRSSExport::fetch( $deleteID, true, eZRSSExport::STATUS_VALID );
        if ( $rssExport )
        {
            $rssExport->remove();
        }
    }
}
else if ( $http->hasPostVariable( 'NewImportButton' ) )
{
    return $Module->run( 'edit_import', array() );
}
else if ( $http->hasPostVariable( 'RemoveImportButton' ) && $http->hasPostVariable( 'DeleteIDArrayImport' ) )
{
    $deleteArray = $http->postVariable( 'DeleteIDArrayImport' );
    foreach ( $deleteArray as $deleteID )
    {
        $rssImport = eZRSSImport::fetch( $deleteID, true, eZRSSImport::STATUS_DRAFT );
        if ( $rssImport )
        {
            $rssImport->remove();
        }
        $rssImport = eZRSSImport::fetch( $deleteID, true, eZRSSImport::STATUS_VALID );
        if ( $rssImport )
        {
            $rssImport->remove();
        }
    }
}


// Get all RSS Exports
$exportArray = eZRSSExport::fetchList();
$exportList = array();
foreach( $exportArray as $export )
{
    $exportList[$export->attribute( 'id' )] = $export;
}

// Get all RSS imports
$importArray = eZRSSImport::fetchList();
$importList = array();
foreach( $importArray as $import )
{
    $importList[$import->attribute( 'id' )] = $import;
}

$tpl = eZTemplate::factory();

$tpl->setVariable( 'rssexport_list', $exportList );
$tpl->setVariable( 'rssimport_list', $importList );

$Result = array();
$Result['content'] = $tpl->fetch( "design:rss/list.tpl" );
$Result['path'] = array( array( 'url' => 'rss/list',
                                'text' => ezpI18n::tr( 'kernel/rss', 'Really Simple Syndication' ) ) );


?>
