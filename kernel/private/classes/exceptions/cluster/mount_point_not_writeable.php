<?php
/**
 * File containing the eZDFSFileHandlerNFSMountPointNotWriteableException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package kernel
 */

/**
 * Class representing a cluster mount point not writeable exception
 *
 * @version 4.5.0
 * @package kernel
 */

class eZDFSFileHandlerNFSMountPointNotWriteableException extends ezcBaseException
{
    /**
     * Constructs a new eZDFSFileHandlerNFSMountPointNotFoundException
     *
     * @param string $path the mount point path
     * @return void
     */
    function __construct( $path )
    {
        parent::__construct( "Local DFS mount point '{$path}' is not writeable" );
    }
}
?>
