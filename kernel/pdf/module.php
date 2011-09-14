<?php
//
// Created on: <18-Dec-2003 13:20:08 kk>
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

$Module = array( 'name' => 'eZContentObject',
                 'variable_params' => true );

$ViewList['edit'] = array(
    'script' => 'edit.php',
    'functions' => array( 'edit' ),
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'ExportPDFBrowse' => 'BrowseSource',
                                    'ExportPDFButton' => 'Export',
                                    'DiscardButton'   => 'Discard',
                                    'CreateExport' => 'CreateExport' ),
    'post_action_parameters' => array( 'Export' => array( 'Title' => 'Title',
                                                          'DisplayFrontpage' => 'DisplayFrontpage',
                                                          'IntroText' => 'IntroText',
                                                          'SubText' => 'SubText',
                                                          'SourceNode' => 'SourceNode',
                                                          'ExportType' => 'ExportType',
                                                          'ClassList' => 'ClassList',
                                                          'SiteAccess' => 'SiteAccess',
                                                          'DestinationType' => 'DestinationType',
                                                          'DestinationFile' => 'DestinationFile' ),
                                       'BrowseSource' => array( 'Title' => 'Title',
                                                                'DisplayFrontpage' => 'DisplayFrontpage',
                                                                'IntroText' => 'IntroText',
                                                                'SubText' => 'SubText',
                                                                'ExportType' => 'ExportType',
                                                                'ClassList' => 'ClassList',
                                                                'SiteAccess' => 'SiteAccess',
                                                                'DestinationType' => 'DestinationType',
                                                                'DestinationFile' => 'DestinationFile' ) ),
    'unordered_params' => array( 'language' => 'Language' ),
    'params' => array( 'PDFExportID', 'PDFGenerate' ) );

$ViewList['list'] = array(
    'script' => 'list.php',
    'functions' => array( 'edit' ),
    'default_navigation_part' => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'NewPDFExport' => 'NewExport',
                                    'RemoveExportButton' => 'RemoveExport' ),
    'post_action_parameters' => array( 'RemoveExport' => array( 'DeleteIDArray' => 'DeleteIDArray' ) ),
    'unordered_params' => array( 'language' => 'Language' ) );


$FunctionList = array();
$FunctionList['create'] = array();
$FunctionList['edit'] = array();

?>
