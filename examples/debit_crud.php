<html>
<head>
  <meta charset="utf-8"/>
  <title>Example > Debit CRUD - Payname SDK</title>
  <style media="screen" type="text/css">
body {
    font-family: monospace;
}
  </style>
</head>
<body>
<h1>DEBIT CRUD</h1>
<?php
require_once('../src/Payname/Payment/Payment.class.php');
require_once('../src/Payname/Payment/Debit.class.php');
use \Payname\Payment\Payment;
use \Payname\Payment\Debit;


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

$order = 'TEST_DEBIT_' . rand(0, 99999);


/* Prereq. Create parent payment */

echo '<h2>Prerequisite: create a parent payment</h2>';
try {
    $oNewPayment = Payment::create(array('order' => $order));
    echo '<strong>Payment created:</strong>' . "\n";
    echo _toHTML($oNewPayment);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Create simple debit */

echo '<h2>Creation</h2>' . "\n";
try {
    $oNewDebit = Debit::create(
        array(
            'payment' => $order
            , 'method' => 'cb'
            , 'amount' => '10,9'
            , 'card' => array(
                'number' => '4970100000000000'
                , 'expiry' => array(
                    'month' => '1'
                    , 'year' => '2018'
                )
                , 'cvv' => '345'
            )
        )
    );
    echo '<strong>Debit created:</strong>' . "\n";
    echo _toHTML($oNewDebit);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Get existing debit */

echo '<h2>Get existing debit</h2>' . "\n";

try {
    // Directly
    $oDebit = Debit::get($order, $oNewDebit->hash);
    echo '<strong>Debit got (directly):</strong>' . "\n";
    echo _toHTML($oDebit);

    // Via payment
    $oDebit = Payment::get($order)->debit($oNewDebit->hash);
    echo '<strong>Debit got (via payment):</strong>' . "\n";
    echo _toHTML($oDebit);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Get debit list */

echo '<h2>Get debit list</h2>' . "\n";

try {
    // Directly
    $aDebits = Debit::getAll($order);
    echo '<strong>Debit list (directly):</strong>' . "\n";
    echo _toHTML($aDebits);

    // Via payment
    $aDebits = Payment::get($order)->debit();
    echo '<strong>Debit list (via payment):</strong>' . "\n";
    echo _toHTML($aDebits);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Update debit */

echo '<h2>Update debit</h2>' . "\n";

try {
    $oDebit = Debit::get($order, $oNewDebit->hash);
    $oDebit->amount = 1337;
    $oDebit->due_at = '2015-09-01';
    $oDebit->update();

    echo '<strong>Updated debit:</strong>' . "\n";
    echo _toHTML($oDebit);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Delete debit */

echo '<h2>Delete debit</h2>' . "\n";

try {
    $aDeletionRes = $oNewDebit->delete();

    echo '<strong>Deleted debit:</strong>' . "\n";
    echo _toHTML($aDeletionRes);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}

