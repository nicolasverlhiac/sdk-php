<html>
<head>
  <meta charset="utf-8"/>
  <title>Example > Payment with token - Payname SDK</title>
  <style media="screen" type="text/css">
body {
    font-family: monospace;
}
  </style>
</head>
<body>
<h1>PAYMENT WITH TOKEN</h1>
<?php

require_once('../src/Payname/Payname.class.php');
require_once('../src/Payname/Payment/Payment.class.php');
require_once('../src/Payname/Card/Card.class.php');
use \Payname\Payment\Payment;
use \Payname\Card\Card;


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


$order = 'TTOKN';
$amount = '10';


/* 1. Token creation */

if (!isset($_POST['PaRes'])) {
    echo '<h2>create a new card token</h2>';
    try {
        $aRes = Card::create(
            array(
                'number' => '4970100000000009' // sampe test card with 3DS
                , 'expiry' => array(
                    'month' => '1'
                    , 'year' => '2018'
                )
                , 'cvv' => '345'
                , 'email' => 'rene@coty.fr'
            )
        );
        $sToken = $aRes['token'];
    } catch (\Payname\Exception $e) {
        echo $e . "\n";
    }
    echo 'Token created: ' . $sToken . "\n";
}


/* 2. Payment creation */

if (!isset($_POST['PaRes'])) {
    echo '<h2>create new payment by itself</h2>';
    try {
        $oNewPayment = Payment::create(
            array(
                'type' => 'token'
                , 'datas' => array(
                    'token' => $sToken
                    , 'amount' => $amount
                    , 'order_id' => $order
                )
            )
        );
    } catch (\Payname\Exception $e) {
        echo $e . "\n";
    }
    echo 'Payment created: ' . "\n";
    echo _toHTML($oNewPayment);
}


/* 3. 3DS validation only if payment in W_3DS mode */

if (
    !isset($_POST['PaRes'])
    and isset($oNewPayment)
    and ($oNewPayment->status == 'W_3DS'))
    :
?>
<h2>Validation 3DS</h2>
<form name="test3DS"
      action="<?php echo $oNewPayment->test_3DS['url']; ?>"
      method="POST"
      >
  <input type="hidden"
         name="PaReq"
         value="<?php echo $oNewPayment->test_3DS['pareq']?>"
         >
  <input type="hidden"
         name="MD"
         value="<?php echo $oNewPayment->test_3DS['transaction']; ?>"
         >
  <input type="hidden"
         name="TermUrl"
         value="http://<?php echo $_SERVER['SERVER_NAME']; ?>/examples/payment_token.php"
         >
  <input type="submit" value="Test 3DS"><br>
</form>

<?php
endif;


$oPayment = Payment::get($order);


/* 4. After 3DS - Confirmation */

if (isset($_POST['PaRes']) and $oPayment->status == 'W_3DS') {
    echo '<h2>Finalization 3DS</h2>';
    $oPayment->finalize_3DS($_POST['PaRes'], $_POST['MD']);

    echo '<p> Result: </p>';
    echo _toHTML($oPayment = Payment::get($order));
}


/* 5. Payment - END */

if ($oPayment->status == 'C_WAITING') {
    echo '<h2>Payment confirmation</h2>';
    $oPayment->confirm();

    echo '<p> Result: </p>';
    echo _toHTML($oPayment = Payment::get($order));
}
?>

</pre>
</body>
</html>
