<?php
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
define( 'TABLE_METADATA', 'ezdfsfile' );

function _die( $value )
{
    header( $_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error" );
    die( $value );
}

// Storage database connection string
/*if ( defined( 'STORAGE_SOCKET' ) && STORAGE_SOCKET !== false )
    $serverString = STORAGE_SOCKET;
elseif ( defined( 'STORAGE_PORT' ) )
    $serverString = STORAGE_HOST . ':' . STORAGE_PORT;
else
    $serverString = STORAGE_HOST;*/
if ( !defined( 'STORAGE_PORT' ) )
{
    define( 'STORAGE_PORT', '' );
}
if ( !defined( 'STORAGE_SOCKET' ) )
{
    define( 'STORAGE_SOCKET', '' );
}

$maxTries = 3;
$tries = 0;
while ( $tries < $maxTries )
{
    /// @todo test if using socket does work
    if ( $db = mysqli_connect( STORAGE_HOST, STORAGE_USER, STORAGE_PASS, STORAGE_DB, STORAGE_PORT, STORAGE_SOCKET ) )
        break;
    ++$tries;
}
if ( $tries > $maxTries )
{
    _die( "Unable to connect to database server.\n" );
}
if ( !$db )
    _die( "Unable to connect to storage server" );

if ( !mysqli_set_charset( $db, defined( 'STORAGE_CHARSET' ) ? STORAGE_CHARSET : 'utf8' ) )
    _die( "Failed to set character set.\n" );

$filename = ltrim( $_SERVER['REQUEST_URI'], '/');
if ( ( $queryPos = strpos( $filename, '?' ) ) !== false )
    $filename = substr( $filename, 0, $queryPos );

// Fetch file metadata.
$filePathHash = md5( $filename );
$sql = "SELECT * FROM " . TABLE_METADATA . " WHERE name_hash=('$filePathHash')" ;
if ( !$res = mysqli_query( $db, $sql ) )
    _die( "Failed to retrieve file metadata\n" );

if ( !( $metaData = mysqli_fetch_assoc( $res ) ) ||
     $metaData['mtime'] < 0 )
{
    header( $_SERVER['SERVER_PROTOCOL'] . " 404 Not Found" );
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>404 Not Found</TITLE>
</HEAD><BODY>
<H1>Not Found</H1>
The requested URL <?php echo htmlspecialchars( $filename ); ?> was not found on this server.<P>
</BODY></HTML>
<?php
    mysqli_close( $db );
    exit( 1 );
}

mysqli_free_result( $res );

// Fetch file data.
$dfsFilePath = MOUNT_POINT_PATH . '/' . $filename;
// Set cache time out to 100 minutes by default
$expiry = defined( 'EXPIRY_TIMEOUT' ) ? EXPIRY_TIMEOUT : 6000;
if ( file_exists( $dfsFilePath ) )
{
    // Output HTTP headers.
    $path     = $metaData['name'];
    $size     = $metaData['size'];
    $mimeType = $metaData['datatype'];
    $mtime    = $metaData['mtime'];
    $mdate    = gmdate( 'D, d M Y H:i:s', $mtime ) . ' GMT';

    header( "Content-Length: $size" );
    header( "Content-Type: $mimeType" );
    header( "Last-Modified: $mdate" );
    header( "Expires: " . gmdate('D, d M Y H:i:s', time() + $expiry) . ' GMT' );
    header( "Connection: close" );
    header( "X-Powered-By: eZ Publish" );
    header( "Accept-Ranges: none" );
    header( 'Served-by: ' . $_SERVER["SERVER_NAME"] );

    // Output image data.
    $fp = fopen( $dfsFilePath, 'r' );
    fpassthru( $fp );
    fclose( $fp );
}
?>
