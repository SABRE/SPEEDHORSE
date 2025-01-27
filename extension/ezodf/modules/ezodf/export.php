<?php
//
// Created on: <10-Nov-2004 11:42:23 bf>
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

require_once( "kernel/common/template.php" );

$http = eZHTTPTool::instance();
$module = $Params["Module"];
$NodeID = $Params['NodeID'];
$exportTypeParam = $Params['ExportType'];

$tpl = templateInit();

$success = true;

if ( $http->hasPostVariable( "ExportButton" ) )
{
    eZContentBrowse::browse( array( 'action_name' => 'OOPlace',
                                    'description_template' => 'design:ezodf/browse_place.tpl',
                                    'content' => array(),
                                    'from_page' => '/ezodf/export/',
                                    'cancel_page' => '/ezodf/export/' ),
                             $module );
    return;
}

$doExport = false;
if ( $module->isCurrentAction( 'OOPlace' ) )
{
    // We have the file and the placement. Do the actual import.
    $selectedNodeIDArray = eZContentBrowse::result( 'OOPlace' );
    $nodeID = $selectedNodeIDArray[0];
    $doExport = true;
}

if ( $http->hasPostVariable( "NodeID" ) )
{
    $nodeID = $http->postVariable( "NodeID" );
    $doExport = true;
}
else if ( is_numeric( $NodeID ) )
{
    $nodeID = $NodeID;
    $doExport = true;
}

$exportType = false;
if ( $http->hasPostVariable( "ExportType" ) )
{
    $type = $http->postVariable( "ExportType" );

    if ( $type == "PDF" or $type == "Word" )
    {
        $exportType = $type;
    }
    else
    {
        $tpl->setVariable( "error_string", ezpI18n::tr( 'extension/ezodf/export/error',"Destination file format not supported" ) );
        $success = false;
    }
}
else if ( $exportTypeParam == "PDF" or $exportTypeParam == "Word" )
{
    $exportType = $exportTypeParam;
}
else if ( strlen( trim( $exportTypeParam ) ) != 0 )
{
    $tpl->setVariable( "error_string", ezpI18n::tr( 'extension/ezodf/export/error', "Destination file format not supported" ) );
    $success = false;
}

$ooINI = eZINI::instance( 'odf.ini' );
//$tmpDir = $ooINI->variable( 'ODFSettings', 'TmpDir' );
$tmpDir = getcwd() . "/" . eZSys::cacheDirectory();

if ( $doExport == true )
{
    if ( is_numeric( $nodeID ) )
    {
        $node = eZContentObjectTreeNode::fetch( $nodeID );

        // Check if we have read access to this node
        if ( $node && $node->canRead() )
        {
            // Do the actual eZ Publish export
            $fileName = eZOOConverter::objectToOO( $nodeID );

            if ( !is_array( $fileName ) )
            {
                $nodeName = $node->attribute( 'name' );

                $originalFileName = $nodeName . ".odt";
                $contentType = "application/vnd.oasis.opendocument.text";

                $trans = eZCharTransform::instance();
                $nodeName = $trans->transformByGroup( $nodeName, 'urlalias' );

                $uniqueStamp = md5( time() );

                $server = $ooINI->variable( "ODFImport", "OOConverterAddress" );
                $port = $ooINI->variable( "ODFImport", "OOConverterPort" );

                switch ( $exportType )
                {
                    case "PDF" :
                    {
                        if ( ( $result = daemonConvert( $server, $port, realpath( $fileName ), "convertToPDF", $tmpDir . "/ooo_converted_$uniqueStamp.pdf" ) ) )
                        {
                            $originalFileName = $nodeName . ".pdf";
                            $contentType = "application/pdf";
                            $fileName = $tmpDir . "/ooo_converted_$uniqueStamp.pdf";
                        }
                        else
                        {
                            $success = false;
                            $tpl->setVariable( "error_string", ezpI18n::tr( 'extension/ezodf/export/error',"PDF conversion failed" ) );
                        }
                    } break;

                    case "Word" :
                    {
                        if ( ( $result = daemonConvert( $server, $port, realpath( $fileName ), "convertToDoc", $tmpDir . "/ooo_converted_$uniqueStamp.doc" ) ) )
                        {
                            $originalFileName = $nodeName . ".doc";
                            $contentType = "application/ms-word";
                            $fileName = $tmpDir . "/ooo_converted_$uniqueStamp.doc";
                        }
                        else
                        {
                            $success = false;
                            $tpl->setVariable( "error_string", ezpI18n::tr( 'extension/ezodf/export/error',"Word conversion failed" ) );
                        }
                    } break;
                }
            }
            else
            {
                $tpl->setVariable( "error_string", $fileName[1] );
                $success = false;
            }
        }
        else
        {
            $tpl->setVariable( "error_string", ezpI18n::tr( 'extension/ezodf/export/error',"Unable to fetch node, or no read access" ) );
            $success = false;
        }

        if ( $success )
        {
            $contentLength = filesize( $fileName );
            if ( $contentLength > 0 )
            {
                $escapedOriginalFileName = addslashes( $originalFileName );

                // Download the file
                header( "Pragma: " );
                header( "Cache-Control: " );
                // Set cache time out to 10 minutes, this should be good enough to work around an IE bug
                header( "Expires: ". gmdate('D, d M Y H:i:s', time() + 600) . 'GMT');
                header( "Content-Length: $contentLength" );
                header( "Content-Type: $contentType" );
                header( "X-Powered-By: eZ Publish" );
                header( "Content-disposition: attachment; filename=\"$escapedOriginalFileName\"" );
                header( "Content-Transfer-Encoding: binary" );

                $fh = fopen( "$fileName", "rb" );

                ob_end_clean();
                fpassthru( $fh );
                fclose( $fh );

                unlink( $fileName );
                eZExecution::cleanExit();
            }
            else
            {
                $tpl->setVariable( "error_string", ezpI18n::tr( 'extension/ezodf/export/error', "Unable to open file %1 on server side", null, array( $fileName ) ) );
            }
        }
    }
}

$Result = array();
$Result['content'] = $tpl->fetch( "design:ezodf/export.tpl" );
$Result['path'] = array( array( 'url' => '/ezodf/export/',
                                'text' => ezpI18n::tr( 'extension/ezodf', 'OpenOffice.org export' ) ) );


/*!
      Connects to the eZ Publish document conversion daemon and converts the document to specified format
*/
function daemonConvert( $server, $port, $sourceFile, $conversionCommand, $destFile )
{
    $fp = fsockopen( $server,
                     $port,
                     $errorNR,
                     $errorString,
                     10 ); // @as 2008-11-25 - Increase the timeout from 0 to 10 to prevent problems with connection

    if ( $fp )
    {
        $welcome = fread( $fp, 1024 );

        $welcome = trim( $welcome );
        if ( $welcome == "eZ Publish document conversion daemon" )
        {
            $commandString = "$conversionCommand $sourceFile $destFile";
            fputs( $fp, $commandString, strlen( $commandString ) );

            $result = fread( $fp, 1024 );
            $result = trim( $result );
        }
        fclose( $fp );

        return $result;
    }
    return false;
}

?>
