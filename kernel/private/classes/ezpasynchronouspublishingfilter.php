<?php
/**
 * File containing the ezpAsynchronousPublishingFilter interface.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package kernel
 */

/**
 * This interface is to be used to create accept/reject filters for the Asynchronous Publishing feature
 * Each filter class must implement the accept() method, that returns either true (to publish asynchronously) or false
 * (to publish synchronously).
 *
 * Filters are registered in content.ini, in the PublishingSettings block:
 * [PublishingSettings]
 * AsynchronousPublishingFilters[]=MyAsynchronousPublishingFilterClass
 *
 * All registered filters will be called sequentially until one returns false, or all of them have been called
 * @package kernel
 */
abstract class ezpAsynchronousPublishingFilter implements ezpAsynchronousPublishingFilterInterface
{
    public function __construct( eZContentObjectVersion $version )
    {
        $this->version = $version;
    }

    /**
     * The content object version candidate for asynchronous publishing
     * @var eZContentObjectVersion
     */
    protected $version;
}
?>
