<?php
/**
 * File containing the eZDFSFileHandlerTableNotFoundException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package kernel
 */

/**
 * Class handling a cluster table not found exception
 *
 * @version 4.5.0
 * @package kernel
 */

class eZDFSFileHandlerTableNotFoundException extends ezcBaseException
{
    /**
     * Constructs a new eZDFSFileHandlerTableNotFoundException
     *
     * @param string $sql The SQL query
     * @param string $error The SQL error message
     * @return void
     */
    function __construct( $sql, $error )
    {
        $message = "Table not found when executing SQL '$sql' ".
                   "(error message: $error).\n" .
                   "Please review the cluster tables installation or configuration";
        parent::__construct( $message );
    }
}
?>
