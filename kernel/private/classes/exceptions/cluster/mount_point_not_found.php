<?php
/**
 * File containing the eZDFSFileHandlerNFSMountPointNotFoundException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package kernel
 */

/**
 * Class representing a cluster mount point not found exception
 *
 * @version 4.5.0
 * @package kernel
 */

class eZDFSFileHandlerNFSMountPointNotFoundException extends ezcBaseException
{
    /**
     * Constructs a new eZDFSFileHandlerNFSMountPointNotFoundException
     *
     * @param string $host The hostname
     * @param string $user The username
     * @param string $pass The password (will be displayed as *)
     * @return void
     */
    function __construct( $path )
    {
        parent::__construct( "Local DFS mount point '{$path}' does not exist" );
    }
}
?>
