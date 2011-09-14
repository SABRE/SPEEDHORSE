<?php
/**
 * File containing the eZClusterHandlerDBNoConnectionException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package kernel
 */

/**
 * Class representing a cluster no connection exception
 *
 * @version 4.5.0
 * @package kernel
 */

class eZClusterHandlerDBNoConnectionException extends eZDBException
{
    /**
     * Constructs a new eZClusterHandlerDBNoConnectionException
     *
     * @param string $host The hostname
     * @param string $user The username
     * @param string $pass The password (will be displayed as *)
     * @return void
     */
    function __construct( $host, $user, $password )
    {
        $password = str_repeat( "*", strlen( $password ) );
        parent::__construct( "Unable to connect to the database server '{$host}' using username '{$user}' and password '{$password}'" );
    }
}
?>
