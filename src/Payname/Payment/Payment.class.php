<?php

/**
 * File payment
 */

namespace Payname\Payment;

require_once(realpath(dirname(__FILE__) . '/../Payname.class.php'));
use \Payname\Payname;


/**
 * Payment
 *
 * @package  Payname
 * @subpackage  Payment
 */
class Payment {

    /**
     * Payment hash, public ID
     * @var  string
     */
    public $hash = '';


    /**
     * Payment order, external ID defined by customer
     * @var  string
     */
    public $order = '';


    /**
     * Payment status
     *
     * Supported values:
     * - Debit phase
     *   - `W_USER_FROM` --> At least one debit user must be validated
     *   - `W_DEBIT` --> At least one debit execution is not finished
     * - Confirmation phase
     *   - `C_DOUBLE_WAITING` --> Awaiting 1st confirmation (`double` confirmation mode only)
     *   - `C_WAITING` --> Awaiting confirmation (`simple` confirmation mode)
     *      or 2nd confirmation (`double` confirmation mode)
     *   - `C_BALANCE` --> Total debit amount and total Credit amount don`t match.
     * - Credit execution phase
     *   - `F_USER_TO` --> At least one Credit user must be validated
     *   - `F_CREDIT` --> At least one Credit execution is not finished
     *   - `F_DONE` --> *Definitive state.* Payment finished
     * - Payment deleted
     *   - `D_ADMIN` --> *Definitive state.*
     *      Payment deleted by shop/marketplace owner
     * - Older deprecated states
	 *   - `W_WAITING` --> Moved to debit states
	 *   - `W_SENDING` --> Moved to debit states
	 *   - `W_TIMEOUT` --> Moved to debit states
	 *   - `W_3DS` --> Moved to debit states
	 *   - `W_IBAN` --> Moved to debit states as W_USER
	 *   - `F_IBAN` --> Moved to credit states as W_USER
	 *   - `F_SEND` --> Moved to credit states as F_SENT
	 *   - `F_RECEIVED` --> moved to credit states as F_DONE
     *
     * @var  string  Enumeration
     */
    public $status = '';


    /**
     * Payment planned due date
     *
     * Used to auto-confirm the payment at a specific date
     * <br/> **Note:** Does *not* force execution of Debit/Credit.
     * Use their own `due_at` instead
     *
     * @var  date
     */
    public $due_at = null;


    /**
     * Payment target amount
     *
     * Used as target for Debits: Auto confirms payment when Debits total amount
     * reaches this value.
     *
     * @var  float
     */
    public $target_amount = null;


    /**
     * Payment confirmation strategy to apply
     *
     * Available strategies:
     * - `double`: Double confirmation. Requires 2 calls to confirm method to
     *   start credit execution process.
     * - `simple`: Simple confirmation. Require only one call to confirm method
     *   to start credit execution process
     * - `none`: No confirmation. Automatically start credit execution process
     *    when all Debits are executed
     *
     * @var  string  Enumeration
     */
    public $confirmation = '';


    /**
     * Option : URSSAF
     *
     * Enables / Disables URSSAF management
     *
     * @link  http://api.payname.fr/documentation/#/how_to/sap
     *
     * @var boolean
     */
    public $option_urssaf = false;



    // -------------------------------------------------------------------------
    // DEPRECATED FIELDS
    // -------------------------------------------------------------------------

    /**
     * Payment amount
     * @deprecated  Use Debit->amount instead
     * @var  float
     */
    public $amount = 0;


    /**
     * Payment urssaf amount
     * @deprecated  Use Credit->amount for URSSAF credit instead
     * @var  float
     */
    public $urssaf = 0;


    /**
     * Payment payname commission amount
     * @deprecated  Use Credit->amount for Payname credit instead
     * @var  float
     */
    public $payname = 0;
    

    /**
     * Payment tax commission amount
     * @deprecated  Use Credit->amount for Marketplace credit instead
     * @var  float
     */
    public $tax = 0;


    /**
     * Payment presentation date
     * @deprecated  Use due_at instead
     * @var  string
     */
    public $presentationDate = '';


    /**
     * 3DS Test data
     * @deprecated  Use each Debit->test_3DS instead
     */
    public $test_3DS = array();



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
     * Creates a new Payment
     *
     * @param  array  $aOptions  Initial values
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Payment  Payment created
     */
    public static function create($aOptions) {
        $aCallOpts = array(
            'url' => '/payment'
            , 'postData' => $aOptions
        );
        $aRes = Payname::post($aCallOpts);

        if (isset($aOptions['datas'])) {
            // Deprecated old way to create paymant
            if (isset($aOptions['datas']['general'])) {
                $oPayment = static::get($aOptions['datas']['general']['order_id']);
            } else {
                $oPayment = static::get($aOptions['datas']['order_id']);
            }
            $oPayment->test_3DS = $aRes['data'];
        } else {
            // Official way: details returned in the response
            $oPayment = new Payment($aRes['data']);
        }

        return $oPayment;
    }


    /**
     * Get an existing Payment
     *
     * @param  string  $sHash  Hash of the Payment to get
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Payment  Corresponding Payment
     */
    public static function get($sHash) {
        $aCallOpts = array(
            'url' => '/payment/' . $sHash
        );
        $aRes = Payname::get($aCallOpts);
        return new Payment($aRes['data']);
    }


    /**
     * Get a list of payments
     *
     * @todo  Implementer pagination
     *
     * @return  array  List of Payments
     */
    public static function getAll() {
        $aCallOpts = array(
            'url' => '/payment'
        );
        $aRes = Payname::get($aCallOpts);
        return $aRes['data'];
     }


    /**
     * Update payment in the platform
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Payment  Current Payment instance
     */
    public function update() {
        $aCallOpts = array(
            'url' => '/payment/' . $this->hash
            , 'postData' => get_object_vars($this)
        );
        $aResult = Payname::put($aCallOpts);
        $this->_load($aResult['data']);
        return $this;
    }


    /**
     * Finalize 3DS test for payment
     *
     * @throw  \Payname\Exception  On API error
     *
     * @param  string  $pares  PaRes returned by 3DS test form
     * @param  string  $trs    Transaction number (MD) returned by 3DS test form
     *
     * @return  mixed  API response, if any
     */
    public function finalize_3DS($pares, $trs) {
        $aCallOpts = array(
            'url' => '/payment'
            , 'postData' => array(
                'action' => 'finalyze_3ds'
                , 'datas' => array(
                    'order_id' => $this->order
                    , 'transaction' => $trs
                    , 'pares' => $pares
                )
            )
        );
        $aResult = Payname::put($aCallOpts);
        return (isset($aResult['data']) ? $aResult['data'] : null);
    }


    /**
     * Confirm payment
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  mixed  API response, if any
     */
    public function confirm() {
        $aCallOpts = array(
            'url' => '/payment'
            , 'postData' => array(
                'action' => 'confirm'
                , 'datas' => array(
                    'order_id' => $this->order
                )
            )
        );
        $aResult = Payname::put($aCallOpts);
        return (isset($aResult['data']) ? $aResult['data'] : null);
    }


    /**
     * Delete payment on the platform
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  Payment  Current payment instance
     */
    public function delete() {
        $aCallOpts = array(
            'url' => '/payment/' . $this->hash
        );
        $aResult = Payname::delete($aCallOpts);
        $this->_load($aResult['data']);
        return $this;
    }



    // -------------------------------------------------------------------------
    // SUB-ENTITIES METHODS
    // -------------------------------------------------------------------------

    /**
     * Get a related debit, or all debits
     *
     * @param  string  $sHash  (Optional) Hash of debit to get
     *                         <br/> Default : null <=> get all related debits
     *
     * @throw  \Payname\Exception  On API Error
     *
     * @return  Debit|array  Requested Debit, or list of all related debits
     */
    public function debit($sHash = null) {
        if ($sHash) {
            // hash given => get one
            $mRes = Debit::get($this->hash, $sHash);
        } else {
            // no hash => get all
            $mRes = Debit::getAll($this->hash);
        }
        return $mRes;
    }


    /**
     * Get a related credit, or all credits
     *
     * @param  string  $sHash  (Optional) Hash of credit to get
     *                         <br/> Default : null <=> get all related credits
     *
     * @throw  \Payname\Exception  On API Error
     *
     * @return  Credit|array  Requested Credit, or list of all related credits
     */
    public function credit($sHash = null) {
        if ($sHash) {
            // hash given => get one
            $mRes = Credit::get($this->hash, $sHash);
        } else {
            // no hash => get all
            $mRes = Credit::getAll($this->hash);
        }
        return $mRes;
    }
}
