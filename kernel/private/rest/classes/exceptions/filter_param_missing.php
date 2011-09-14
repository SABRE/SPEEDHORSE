<?php
/**
 * File containing the ezpRestFilterMissingParameterException exception
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

class ezpRestFilterMissingParameterException extends ezpRestException
{
    public function __construct()
    {
        parent::__construct( "Missing parameter for filter constructor." );
    }
}
