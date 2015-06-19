<?php
/**
 * File Popup
 */

namespace Payname\Popup;

require_once(realpath(dirname(__FILE__) . '/../Payname.class.php'));


/**
 * Popup management
 *
 * @package  Payname
 * @subpackage  Popup
 */
class Popup {

    /**
     * Create a popup for current shop
     *
     * @param  float  $fAmount  Amount to pay
     *
     * @throw  \Payname\Exception  On API error
     *
     * @return  string  URL to open in popup
     */
    public static function create($fAmount) {
        $aRes = \Payname\Payname::post(
            array(
                'url' => '/popup'
                , 'postData' => array(
                    'amount' => 0.99
                )
            )
        );
        return $aRes['data']['url'];
    }
}
