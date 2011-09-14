<?php
/**
 * File containing the ezpAsynchronousPublisherOutput interface
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package
 */

/**
 * This interface is used as the basis for the ezpasynchronouspublisher.php daemon
 * @package
 */
interface ezpAsynchronousPublisherOutput
{
    /**
     * Write a message to the output
     * @param string $message
     */
    public function write( $message );
}
?>
