<?php

/**
 * File credit
 */

namespace Payname\Payment;

require_once(realpath(dirname(__FILE__) . '/../Payname.class.php'));
use \Payname\Payname;


/**
 * Credit
 *
 * @package  Payname
 * @subpackage  Credit
 */
class Credit {

    /**
     * Credit hash, public ID
     * @var  string
     */
    public $hash = '';


    /**
     * Parent payment hash
     * @var  string
     */
    public $payment = '';


    /**
     * Credit transfert method
     *
     * Avalable methods:
     * - `iban` Credit via IBAN
     * - `Marketplace` *Reserved* Represents marketplace commission
     * - `Payname` *Reserved* Represents payname commission
     *
     * @var  string  Enumeration
     */
    public $method = '';


    /**
     * Credit status
     *
     * Supported values:
     * - `W_USER` -> User not validated. Either because of missing required data
     *   or pending support validation
     * - `W_METHOD` -> Validating transfer method and authorizations
     *   Ex. IBAN authorization, etc.
     * - `W_EXEC` -> User and authorizations OK, waiting for actual transfer
     *   order.
     *   <br/>Ex. if `due_at` is set, credit will stay in this state till due
     *   date is reached.
     * - `F_SENT` -> Transfer order send to bank
     * - `F_DONE` -> *Definitive state.* Transfer confirmed by bank, Credit finished
     * - `D_CANCELLED` -> *Definitive state.* Credit cancelled
     *
     * @var  string  Enumeration
     */
    public $status = '';


    /**
     * Credit planned due date
     *
     * Used to block credit execution before a specific date
     *
     * @var  date
     */
    public $due_at = null;


    /**
     * Credit actual transfert date
     *
     * @var  date
     */
    public $paid_at = null;


    /**
     * Credit amount
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
     * Creates a new Credit
     *
     * @param  array  $aOptions  Initial values
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Credit  Credit created
     */
    public static function create($aOptions) {
        $sPaymentHash = $aOptions['payment'];
        $aCallOpts = array(
            'url' => '/payment/' . $sPaymentHash . '/credit'
            , 'postData' => $aOptions
        );
        $aRes = Payname::post($aCallOpts);

        $oCredit = new Credit($aRes['data']);
        $oCredit->payment = $aOptions['payment'];
        return $oCredit;
    }


    /**
     * Get an existing Credit
     *
     * @param  string  $sPaymentHash  Hash of parent payment
     * @param  string  $sHash         Hash of the Credit to get
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Credit  Corresponding Credit
     */
    public static function get($sPaymentHash, $sHash) {
        $aCallOpts = array(
            'url' => '/payment/' . $sPaymentHash . '/credit/' . $sHash
        );
        $aRes = Payname::get($aCallOpts);
        $oCredit = new Credit($aRes['data']);
        $oCredit->payment = $sPaymentHash;
        return $oCredit;
    }


    /**
     * Get a list of credits
     *
     * @param  string  $sPaymentHash  Hash of parent payment
     *
     * @todo  Implementer pagination
     *
     * @return  array  List of Credits
     */
    public static function getAll($sPaymentHash) {
        $aCallOpts = array(
            'url' => '/payment/' . $sPaymentHash . '/credit'
        );
        $aRes = Payname::get($aCallOpts);
        return $aRes['data'];
     }


    /**
     * Update credit in the platform
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Credit  Current Credit instance
     */
    public function update() {
        $aCallOpts = array(
            'url' => '/payment/' . $this->payment . '/credit/' . $this->hash
            , 'postData' => get_object_vars($this)
        );
        $aResult = Payname::put($aCallOpts);
        $this->_load($aResult['data']);
        return $this;
    }


    /**
     * Delete credit on the platform
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Credit  Current credit instance
     */
    public function delete() {
        $aCallOpts = array(
            'url' => '/payment/' . $this->payment . '/credit/' . $this->hash
        );
        $aResult = Payname::delete($aCallOpts);
        $this->_load($aResult['data']);
        return $this;
    }
}
