<?php

/**
 * File Doc
 */

namespace Payname\User;

require_once(realpath(dirname(__FILE__) . '/../Payname.class.php'));
use \Payname\Payname;


/**
 * User documents
 *
 * @todo  doc type a verifier
 * @todo  Methode de transformation binaire => dataURI
 *
 * @package  Payname
 * @subpackage  User
 */
class Doc {

    /**
     * Document hash
     * @var  string
     */
    public $hash = '';


    /**
     * Document type
     * @var  string
     */
    public $type = null;


    /**
     * File content, base64 encoded
     * @var  string
     */
    public $file = null;


    /**
     * Parent user hash
     * @var  string
     */
    public $user = null;



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
     * Create a new Doc
     *
     * @param  array  $aOptions  Initial values
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Doc  Doc created
     */
    public static function create($aOptions) {
        $sUserHash = $aOptions['user'];
        $aCallOpts = array(
            'url' => '/user/' . $sUserHash . '/doc'
            , 'postData' => $aOptions
        );
        $aRes = Payname::post($aCallOpts);
        $oUser = static::get($sUserHash, $aRes['data']['doc_hash']);
        return $oUser;
    }


    /**
     * Get an existing Doc
     *
     * @param  string  $sUserHash  Hash of parent user
     * @param  string  $sHash      Hash of the Doc to get
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Doc  Corresponding Doc
     */
    public static function get($sUserHash, $sHash) {
        $aCallOpts = array(
            'url' => '/user/' . $sUserHash . '/doc/' . $sHash
        );
        $aRes = Payname::get($aCallOpts);
        return new Doc($aRes['data']);
    }


    /**
     * Get a list of docs
     *
     * @param  string  $sUserHash  Hash of parent user
     *
     * @todo  Implementer pagination
     *
     * @return  array  List of Docs
     */
    public static function getAll($sUserHash) {
        $aCallOpts = array(
            'url' => '/user/' . $sUserHash . '/doc'
        );
        $aRes = Payname::get($aCallOpts);
        return $aRes['data'];
     }


    /**
     * Delete a doc
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  mixed  API response, if any
     */
    public function delete() {
        $aCallOpts = array(
            'url' => '/user/' . $this->user . '/doc/' . $this->hash
        );
        $aResult = Payname::delete($aCallOpts);
        return (isset($aResult['data']) ? $aResult['data'] : null);
    }
}
