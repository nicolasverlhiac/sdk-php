<?php

/**
 * File card
 */

namespace Payname\Card;

require_once(realpath(dirname(__FILE__) . '/../Payname.class.php'));
use \Payname\Payname;


/**
 * Card
 *
 * @package  Payname
 * @subpackage  Card
 */
class Card {

    // no public fields, card data should not be stored

    /**
     * Create a new card token
     *
     * @param  array  $aOptions  Creation options :
     * - number: string, Number of card
     * - expiry: array, Expiry date of the card, with:
     *   - year: integer
     *   - month: integer
     * - security: string, Security code (CVV, CVC, etc.)
     * - user: string, Email or hash of the owner of the card
     *
     * @return  array  API response data
     */
    public static function create($aOptions) {
        $aCallOpts = array(
            'url' => '/token'
            , 'postData' => $aOptions
        );
        $aRes = Payname::post($aCallOpts);
        return $aRes['data'];
    }

}