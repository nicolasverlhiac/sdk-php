<?php

/**
 * File debit
 */

namespace Payname\Payment;

require_once(realpath(dirname(__FILE__) . '/../Payname.class.php'));
use \Payname\Payname;


/**
 * Debit
 *
 * @package  Payname
 * @subpackage  Debit
 */
class Debit {

    /**
     * Debit hash, public ID
     * @var  string
     */
    public $hash = '';


    /**
     * Parent payment hash
     * @var  string
     */
    public $payment = '';


    /**
     * Debit transfert method
     *
     * Avalable methods:
     * - `cb` Debit via payment card
     * - `token` Debit via token of previously registered card
     * - `iban` Debit via IBAN
     *
     * @var  string  Enumeration
     */
    public $method = '';


    /**
     * Debit status
     *
     * Supported values:
     * - `W_USER` -> User not validated. Either because of missing required data
     *   or pending support validation
     * - `W_METHOD` -> Validating transfer method and authorizations
     *   Ex. 3DSecure, IBAN authorization, etc.
     * - `W_EXEC` -> User and authorizations OK, waiting for actual transfer
     *   order.
     *   <br/>Ex. if `due_at` is set, debit will stay in this state till due
     *   date is reached.
     * - `F_SENT` -> Transfer order send to bank
     * - `F_DONE` -> *Definitive state.* Transfer confirmed by bank, Debit finished
     * - `D_CANCELLED` -> *Definitive state.* Debit cancelled
     *
     * @var  string  Enumeration
     */
    public $status = '';


    /**
     * Debit planned due date
     *
     * Used to block debit execution before a specific date
     *
     * @var  date
     */
    public $due_at = null;


    /**
     * Debit actual transfert date
     *
     * @var  date
     */
    public $paid_at = null;


    /**
     * Debit amount
     *
     * @var  float
     */
    public $amount = null;



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
     * Creates a new Debit
     *
     * @param  array  $aOptions  Initial values
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Debit  Debit created
     */
    public static function create($aOptions) {
        $sPaymentHash = $aOptions['payment'];
        $aCallOpts = array(
            'url' => '/payment/' . $sPaymentHash . '/debit'
            , 'postData' => $aOptions
        );
        $aRes = Payname::post($aCallOpts);

        $oDebit = new Debit($aRes['data']);
        $oDebit->payment = $aOptions['payment'];
        return $oDebit;
    }


    /**
     * Get an existing Debit
     *
     * @param  string  $sPaymentHash  Hash of parent payment
     * @param  string  $sHash         Hash of the Debit to get
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Debit  Corresponding Debit
     */
    public static function get($sPaymentHash, $sHash) {
        $aCallOpts = array(
            'url' => '/payment/' . $sPaymentHash . '/debit/' . $sHash
        );
        $aRes = Payname::get($aCallOpts);
        $oDebit = new Debit($aRes['data']);
        $oDebit->payment = $sPaymentHash;
        return $oDebit;
    }


    /**
     * Get a list of debits
     *
     * @param  string  $sPaymentHash  Hash of parent payment
     *
     * @todo  Implementer pagination
     *
     * @return  array  List of Debits
     */
    public static function getAll($sPaymentHash) {
        $aCallOpts = array(
            'url' => '/payment/' . $sPaymentHash . '/debit'
        );
        $aRes = Payname::get($aCallOpts);
        return $aRes['data'];
     }


    /**
     * Update debit in the platform
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Debit  Current Debit instance
     */
    public function update() {
        $aCallOpts = array(
            'url' => '/payment/' . $this->payment . '/debit/' . $this->hash
            , 'postData' => get_object_vars($this)
        );
        $aResult = Payname::put($aCallOpts);
        $this->_load($aResult['data']);
        return $this;
    }


    /**
     * Delete debit on the platform
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Debit  Current debit instance
     */
    public function delete() {
        $aCallOpts = array(
            'url' => '/payment/' . $this->payment . '/debit/' . $this->hash
        );
        $aResult = Payname::delete($aCallOpts);
        $this->_load($aResult['data']);
        return $this;
    }
}
