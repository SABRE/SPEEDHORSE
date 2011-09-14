<?php
/**
 * File containing the eZDBNoConnectionException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package kernel
 */

/**
 * Class representing a no connection exception
 *
 * @version 4.5.0
 * @package kernel
 */

class eZDBNoConnectionException extends eZDBException
{
    /**
     * Constructs a new eZDBNoConnectionException
     *
     * @param string $host The hostname
     * @return void
     */
    function __construct( $host, $errorMessage, $errorNumber )
    {
        parent::__construct( "Unable to connect to the database server '{$host}'\nError #{$errorNumber}: {$errorMessage}" );
    }
}
?>
