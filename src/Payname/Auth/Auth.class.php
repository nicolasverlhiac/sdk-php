<?php

/**
 * File Auth
 */

namespace Payname\Auth;

require_once(realpath(dirname(__FILE__) . '/../Payname.class.php'));


/**
 * Authentification manager
 *
 * @package  Payname
 * @subpackage  Auth
 */
class Auth {

    /**
     * Ask for a token
     */
    public static function token() {
        $aOptions = array(
            'url' => '/auth/token'
            , 'postData' => array(
                'ID' => \Payname\Config::ID
                , 'secret' => \Payname\Config::SECRET
            )
        );

        \Payname\Payname::post($aOptions);
    }


    /**
     * Refresh a token
     */
    public static function refreshToken() {
        $aOptions = array(
            'url' => '/auth/refresh_token'
            , 'postData' => array(
                'ID' => \Payname\Config::ID
                , 'token' => \Payname\Payname::token()
            )
        );

        \Payname\Payname::post($aOptions);
    }
}
