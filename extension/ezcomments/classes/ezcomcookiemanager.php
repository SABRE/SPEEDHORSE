<?php
/**
 * File containing ezcomCookieManager class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/software/proprietary_license_options/ez_proprietary_use_license_v1_0 eZ Proprietary Use License v1.0
 *
 */

/**
 *
 * handle cookies in comment system
 *
 */
class ezcomCookieManager
{
    public $expiryTime = 0 ;

    /**
     * construct
     */
    public function __construct()
    {
        $this->expiryTime = time() + 60 * 60 * 24 * 365;
    }

    /**
     * store data into cookie
     * if field is null, set cookie based on user data, other wise set cookie based on fields
     * @param $comment comment object
     * @return arrary stored data
     */
    public function storeCookie( $comment = null )
    {
        $userData = array();
        $sessionID = session_id();
        if( is_null( $comment ) )
        {
            $currentUser = eZUser::currentUser();
            if( $currentUser->isAnonymous() )
            {
                return '';
            }
            else
            {
                $userData[$sessionID] = array( 'email' => $currentUser->attribute( 'email' ),
                                               'name' => $currentUser->contentObject()->name() );
            }
        }
        else
        {
            $userData[$sessionID] = array( 'email' => $comment->attribute( 'email' ),
                                           'name' => $comment->attribute( 'name' ) );
        }
        setcookie( 'eZCommentsUserData', base64_encode( json_encode( $userData ) ), $this->expiryTime, '/' );
        return $userData;
    }

    /**
     * clear all cookies
     * @return
     */
    public function clearCookie()
    {
        $deleteTime = time() - 3600;
        setcookie( 'eZCommentsUserData', '', $deleteTime, '/' );
    }


    /**
     * create instance
     * @return ezcomCookieMangaer
     */
    public static function instance()
    {
        return new ezcomCookieManager();
    }

}
?>
