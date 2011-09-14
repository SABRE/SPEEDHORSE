<?php
/**
 * File containing the ezpOauthNoAuthInfoException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

/**
 * This exception is thrown when the client did not provide any authentication
 * information in the request.
 *
 * @package oauth
 */
class ezpOauthNoAuthInfoException extends ezpOauthRequiredException
{
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>
