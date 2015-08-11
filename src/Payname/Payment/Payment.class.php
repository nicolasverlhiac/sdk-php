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
     * Payment hash
     * @var  string
     */
    public $hash = '';


    /**
     * Payment order_id, external ID defined by customer
     * @var  string
     */
    public $order_id = '';


    /**
     * Payment amount
     * @var  float
     */
    public $amount = 0;


    /**
     * Payment urssaf amount
     * @var  float
     */
    public $urssaf = 0;


    /**
     * Payment payname commission amount
     * @var  float
     */
    public $payname = 0;
    

    /**
     * Payment tax commission amount
     * @var  float
     */
    public $tax = 0;


    /**
     * Payment presentation date
     * @var  string
     */
    public $presentationDate = '';


    /**
     * Payment status
     * @var  string
     */
    public $status = '';


    /**
     * 3DS Test data
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

        if (isset($aOptions['datas']['general'])) {
            $oPayment = static::get($aOptions['datas']['general']['order_id']);
        } else {
            $oPayment = static::get($aOptions['datas']['order_id']);
        }
        $oPayment->test_3DS = $aRes['data'];

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
     * @return  mixed  API response, if any
     */
    public function update() {
        $aCallOpts = array(
            'url' => '/payment/' . $this->hash
            , 'postData' => get_object_vars($this)
        );
        $aResult = Payname::put($aCallOpts);
        return (isset($aResult['data']) ? $aResult['data'] : null);
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
                    'order_id' => $this->order_id
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
                    'order_id' => $this->order_id
                )
            )
        );
        $aResult = Payname::put($aCallOpts);
        return (isset($aResult['data']) ? $aResult['data'] : null);
    }
}
