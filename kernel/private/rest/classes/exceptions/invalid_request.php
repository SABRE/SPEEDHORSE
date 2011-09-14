<?php
/**
 * File containing the ezpOauthTokenNotFoundException class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

/**
 * This exception is thrown when a request is invalid.
 *
 * An invalid request is the case when a required parameter is missing, when
 * multiple methods of transferring the token is used, or when parameters are
 * repeated.
 *
 * @package oauth
 */
class ezpOauthInvalidRequestException extends ezpOauthBadRequestException
{
    public function __construct( $message )
    {
        $this->errorType = ezpOauthErrorType::INVALID_REQUEST;
        parent::__construct( $message );
    }
}
?>
