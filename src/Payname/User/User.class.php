<?php

/**
 * File User
 */

namespace Payname\User;

require_once(realpath(dirname(__FILE__) . '/../Payname.class.php'));
use \Payname\Payname;


/**
 * User
 *
 * @package  Payname
 * @subpackage  User
 */
class User {

    /**
     * User hash
     * @var  string
     */
    public $hash = '';


    /**
     * User Email address
     * @var  string
     */
    public $email = null;


    /**
     * User phone number
     * @var  string
     */
    public $phone = null;


    /**
     * User first name
     * @var  string
     */
    public $first_name = null;


    /**
     * User last name
     * @var  string
     */
    public $last_name = null;



    // -------------------------------------------------------------------------
    // PROTECTED METHODS
    // -------------------------------------------------------------------------

    /**
     * Class constructor
     *
     * @param  array  $aFields  Field initial values
     */
    protected function __construct($aFields = array()) {
        $this->_load($aFields);
    }


    /**
     * Load instance fields, erase any existing value
     *
     * Used to create/update an instance at once
     *
     * @param  array  $aFields  Field values to set
     */
    protected function _load($aFields = array()) {
        foreach ($aFields as $sKey => $mValue) {
            if (property_exists($this, $sKey)) {
                $this->{$sKey} = $mValue;
            }
        }
    }



    // -------------------------------------------------------------------------
    // PUBLIC METHODS
    // -------------------------------------------------------------------------

    /**
     * Creates a new User
     *
     * @param  array  $aOptions  Initial values
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  User  User created
     */
    public static function create($aOptions) {
        $aCallOpts = array(
            'url' => '/user'
            , 'postData' => $aOptions
        );
        $aRes = Payname::post($aCallOpts);
        $oUser = static::get($aRes['data']);
        return $oUser;
    }


    /**
     * Get an existing User
     *
     * @param  string  $sHash  Hash of the User to get
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  User  Corresponding User
     */
    public static function get($sHash) {
        $aCallOpts = array(
            'url' => '/user/' . $sHash
        );
        $aRes = Payname::get($aCallOpts);
        return new User($aRes['data']);
    }


    /**
     * Get a list of users
     *
     * @todo  Implementer pagination
     *
     * @return  array  List of Users
     */
    public static function getAll() {
        $aCallOpts = array(
            'url' => '/user'
        );
        $aRes = Payname::get($aCallOpts);
        return $aRes['data'];
     }


    /**
     * Update user in the platform
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  mixed  API response, if any
     */
    public function update() {
        $aCallOpts = array(
            'url' => '/user/' . $this->hash
            , 'postData' => get_object_vars($this)
        );
        $aResult = Payname::put($aCallOpts);
        return (isset($aResult['data']) ? $aResult['data'] : null);
    }


    /**
     * Delete user on the platform
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  mixed  API response, if any
     */
    public function delete() {
        $aCallOpts = array(
            'url' => '/user/' . $this->hash
        );
        $aResult = Payname::delete($aCallOpts);
        return (isset($aResult['data']) ? $aResult['data'] : null);
    }

}
