<?php
/**
 * File containing the ezpAsynchronousPublisherLogOutput class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package kernel
 */

/**
 * Handles asynchronous publishing output to var/log/async.log
 * @package kernel
 */
class ezpAsynchronousPublisherLogOutput implements ezpAsynchronousPublisherOutput
{
    private $logFile = 'async.log';
    private $logDir = 'var/log';

    public function write( $message )
    {
        eZLog::write( $message, $this->logFile, $this->logDir );
    }
}
?>
