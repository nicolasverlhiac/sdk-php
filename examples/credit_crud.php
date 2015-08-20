<html>
<head>
  <meta charset="utf-8"/>
  <title>Example > Credit CRUD - Payname SDK</title>
  <style media="screen" type="text/css">
body {
    font-family: monospace;
}
  </style>
</head>
<body>
<h1>CREDIT CRUD</h1>
<?php
require_once('../src/Payname/Payment/Payment.class.php');
require_once('../src/Payname/Payment/Credit.class.php');
use \Payname\Payment\Payment;
use \Payname\Payment\Credit;


/**
 * Helper function to pretty print an object into HTML (ul/li)
 *
 * @param  miwed  $mData  Object|array|scalar to pretty print
 *
 * @param  string  Corresponding HTML code
 */
function _toHTML($mData) {
    $shtml = '';

    if (is_object($mData)) {
        $mData = get_object_vars($mData);
    }
    if (is_array($mData)) {
        $sHTML = '<ul>';
        foreach ($mData as $sKey => $sValue) {
            $sHTML .= '<li>'
                . '<strong>' . $sKey . ':</strong>&nbsp;'
                . _toHTML($sValue)
                . '</li>';
        }
        $sHTML .= '</ul>';
    } else {
        $sHTML = $mData;
    }
    return $sHTML;
}

$order = 'TEST_CREDIT_' . rand(0, 99999);


/* Prereq. Create parent payment */

echo '<h2>Prerequisite: create a parent payment</h2>';
try {
    $oNewPayment = Payment::create(array('order' => $order));
    echo '<strong>Payment created:</strong>' . "\n";
    echo _toHTML($oNewPayment);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Create simple credit */

echo '<h2>Creation</h2>' . "\n";
try {
    $oNewCredit = Credit::create(
        array(
            'payment' => $order
            , 'method' => 'iban'
            , 'amount' => '10,9'
        )
    );
    echo '<strong>Credit created:</strong>' . "\n";
    echo _toHTML($oNewCredit);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Get existing credit */

echo '<h2>Get existing credit</h2>' . "\n";

try {
    // Directly
    $oCredit = Credit::get($order, $oNewCredit->hash);
    echo '<strong>Credit got (directly):</strong>' . "\n";
    echo _toHTML($oCredit);

    // Via payment
    $oCredit = Payment::get($order)->credit($oNewCredit->hash);
    echo '<strong>Credit got (via payment):</strong>' . "\n";
    echo _toHTML($oCredit);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Get credit list */

echo '<h2>Get credit list</h2>' . "\n";

try {
    // Directly
    $aCredits = Credit::getAll($order);
    echo '<strong>Credit list (directly):</strong>' . "\n";
    echo _toHTML($aCredits);

    // Via payment
    $aCredits = Payment::get($order)->credit();
    echo '<strong>Credit list (via payment):</strong>' . "\n";
    echo _toHTML($aCredits);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Update credit */

echo '<h2>Update credit</h2>' . "\n";

try {
    $oCredit = Credit::get($order, $oNewCredit->hash);
    $oCredit->amount = 1337;
    $oCredit->due_at = '2015-09-01';
    $oCredit->update();

    echo '<strong>Updated credit:</strong>' . "\n";
    echo _toHTML($oCredit);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Delete credit */

echo '<h2>Delete credit</h2>' . "\n";

try {
    $aDeletionRes = $oNewCredit->delete();

    echo '<strong>Deleted credit:</strong>' . "\n";
    echo _toHTML($aDeletionRes);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}

