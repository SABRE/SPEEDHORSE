<?php
/**
 * File containing the ezpOauthBadRequestException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

/**
 * This is the base exception for triggering BAD REQUEST response.
 *
 * @package oauth
 */
abstract class ezpOauthBadRequestException extends ezpOauthException
{
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>
