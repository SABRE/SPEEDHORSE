<?php
/**
 * File containing the ezpOauthErrorType
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

class ezpOauthErrorType
{
    const INVALID_REQUEST = 'invalid_request';
    const INVALID_TOKEN = 'invalid_token';
    const EXPIRED_TOKEN = 'expired_token';
    const INSUFFICIENT_SCOPE = 'insufficient_scope';

    public static function httpCodeforError( $error )
    {
        // These HTTP response codes are extracted from Section 5.2.1 of the oauth2.0 spec.
        switch ( $error )
        {
            case self::INVALID_REQUEST:
                return ezpHttpResponseCodes::BAD_REQUEST;
                break;
            case self::INVALID_TOKEN:
            case self::EXPIRED_TOKEN:
                return ezpHttpResponseCodes::UNAUTHORIZED;
                break;
            case self::INSUFFICIENT_SCOPE:
                return ezpHttpResponseCodes::FORBIDDEN;
                break;
            default:
                return ezpHttpResponseCodes::SERVER_ERROR;
                break;
        }
    }
}
?>
