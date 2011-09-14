<?php
/**
 * File containing the eZClusterHandlerDBNoDatabaseException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package kernel
 */

/**
 * Class representing a no database exception
 *
 * @version 4.5.0
 * @package kernel
 */

class eZClusterHandlerDBNoDatabaseException extends eZDBException
{
    /**
     * Constructs a new eZClusterHandlerDBNoDatabaseException
     *
     * @param string $dbname The database
     * @return void
     */
    function __construct( $dbname )
    {
        parent::__construct( "Unable to select the cluster database {$dbname}" );
    }
}
?>
