<?php
/**
 * File containing ezcomServerFunctions class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

/*
 * ezjscServerFunctions for ezcomments
 */

class ezcomServerFunctions extends ezjscServerFunctions
{

    public static function userData()
    {
        unset( $_COOKIE['eZCommentsUserData'] );
        $cookie = ezcomCookieManager::instance();
        return $cookie->storeCookie();
    }
}
