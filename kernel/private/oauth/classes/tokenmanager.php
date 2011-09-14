<?php
/**
 * File containing the ezpRestTokenManager class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 * @version 4.5.0
 * @package oAuth
 */

/**
 * This class handles application tokens
 * @package oAuth
 */

class ezpRestTokenManager
{
    /**
     * Generates a token
     *
     * @todo See if this should maybe return a token object with token + expiry
     *
     * @param string $scope
     *
     * @return string $token
     */
    public static function generateToken( $scope )
    {
        return md5( 'uniqid' );
    }
}
?>
