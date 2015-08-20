<html>
<head>
  <meta charset="utf-8"/>
  <title>Example > Payment CRUD - Payname SDK</title>
  <style media="screen" type="text/css">
body {
    font-family: monospace;
}
  </style>
</head>
<body>
<h1>PAYMENT CRUD</h1>
<?php
require_once('../src/Payname/Payment/Payment.class.php');
use \Payname\Payment\Payment;


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

$order = 'TEST_SIMPLE_' . rand(0, 99999);


/* Create simple payment */

echo '<h2>Creation</h2>' . "\n";
try {
    $oNewPayment = Payment::create(
        array(
            'order' => $order
        )
    );
    echo '<strong>Payment created:</strong>' . "\n";
    echo _toHTML($oNewPayment);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Get existing payment */

echo '<h2>Get existing payment</h2>' . "\n";

try {
    $oPayment = Payment::get($order);
    echo '<strong>Payment got:</strong>' . "\n";
    echo _toHTML($oPayment);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Get payment list */

echo '<h2>Get payment list</h2>' . "\n";

try {
    $aPayments = Payment::getAll();
    echo '<strong>Payment list:</strong>' . "\n";
    echo _toHTML($aPayments);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Update payment */

echo '<h2>Update payment</h2>' . "\n";

try {
    $oPayment = Payment::get($order);
    $oPayment->confirmation = 'double';
    $oPayment->target_amount = 120;
    $oPayment->due_at = null;
    $oPayment->update();

    echo '<strong>Updated payment:</strong>' . "\n";
    echo _toHTML($oPayment);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


/* Delete payment */

echo '<h2>Delete payment</h2>' . "\n";

try {
    $oPayment = Payment::get($order);
    $oPayment->delete();

    echo '<strong>Deleted payment:</strong>' . "\n";
    echo _toHTML($oPayment);
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}

