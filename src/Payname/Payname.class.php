<?php

/**
 * File Payname
 */

namespace Payname;

require_once(realpath(dirname(__FILE__) . '/Exception.class.php'));

if (realpath(dirname(__FILE__) . '/Config.class.php') == '') {
    // Config.class.php not found => error
    throw new \Payname\Exception(
        'ERROR - Config file not found.'
        . ' Cf. Config.class.php.sample for an example config file.'
    );
}
require_once(realpath(dirname(__FILE__) . '/Config.class.php'));


/**
 * Main SDK class
 *
 * @package  Payname
 */
class Payname {


    /**
     * Auth token
     */
    private static $_token = '';



    // -------------------------------------------------------------------------
    // PRIVATE API METHODS
    // -------------------------------------------------------------------------


    /**
     * Call URL
     *
     * @param  array  $aOptions  Call options
     *
     * @throws  Exception  On API error
     *
     * @return  array  API response
     */
    private static function _call($aOptions) {

        if (!\Payname\Config::OAUTH) {
            static::token(\Payname\Config::SECRET);
        }

        $aOpts = array(
            CURLOPT_URL => \Payname\Config::HOST . $aOptions['url']
            , CURLOPT_RETURNTRANSFER => true
            , CURLOPT_CUSTOMREQUEST => $aOptions['method']
            , CURLOPT_HTTPHEADER => array('Authorization: ' . static::$_token)
        );

        if (isset($aOptions['postData'])) {
            $aOpts[CURLOPT_POSTFIELDS] = http_build_query($aOptions['postData']);
        }

        $oCurl = curl_init();
        curl_setopt_array($oCurl, $aOpts);

        $aResult = json_decode(curl_exec($oCurl), true);

        if (!$aResult['success']) {
            throw new Exception(
                $aResult['error']
                . ' - ' . $aResult['msg']
                . (isset($aResult['data'])
                    ? ' - ' . json_encode($aResult['data'], JSON_UNESCAPED_UNICODE)
                    : ''
                )
            );
        }

        return $aResult;
    }



    // -------------------------------------------------------------------------
    // PUBLIC REQUESTS METHODS
    // -------------------------------------------------------------------------

    /**
     * Set and/or return current token
     *
     * @param  string  $sToken  (Optional) New token to set, obtained via \Payname\Auth methods
     *                          Default : null <=> only return current token without setting
     */
    public static function token($sToken = null) {
        if (!is_null($sToken)) {
            static::$_token = $sToken;
        }
        return static::$_token;
    }


    /**
     * GET request
     *
     * @param   array  $aOptions  GET request options
     *
     * @throws  Exception  On API error
     *
     * @return  array  API response
     */
    public static function get($aOptions) {
        $aOptions['method'] = 'GET';
        return static::_call($aOptions);
    }


    /**
     * POST request
     *
     * @param   array  $aOptions  POST request options
     *
     * @throws  Exception  On API error
     *
     * @return  array  API response
     */
    public static function post($aOptions) {
        $aOptions['method'] = 'POST';
        return static::_call($aOptions);
    }


    /**
     * PUT request
     *
     * @param   array  $aOptions  PUT request options
     *
     * @throws  Exception  On API error
     *
     * @return  array  API response
     */
    public static function put($aOptions) {
        $aOptions['method'] = 'PUT';
        return static::_call($aOptions);
    }


    /**
     * DELETE request
     *
     * @param   array  $aOptions  DELETE request options
     *
     * @throws  Exception  On API error
     *
     * @return  array  API response
     */
    public static function delete($aOptions) {
        $aOptions['method'] = 'DELETE';
        return static::_call($aOptions);
    }
}
