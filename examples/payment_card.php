<html>
<head>
  <meta charset="utf-8"/>
  <title>Example > Payment with card - Payname SDK</title>
  <style media="screen" type="text/css">
body {
    font-family: monospace;
}
  </style>
</head>
<body>
<h1>PAYMENT WITH CARD</h1>
<?php

require_once('../src/Payname/Payname.class.php');
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


$order = 'TCARD'; // todo : HTML form to set it from page?
$amount = '10';


/* 1. Payment creation */

if (!isset($_POST['PaRes'])) {
    echo '<h2>Create new payment</h2>';
    try {
        $oNewPayment = Payment::create(
            array(
                'type' => 'cb'
                , 'datas' => array(
                    'general' => array(
                        'amount' => $amount
                        , 'order_id' => $order
                    )
                    , 'card' => array(
                        'number' => '4970100000000009'
                        , 'expiry' => array(
                            'month' => '1'
                            , 'year' => '2018'
                        )
                        , 'cvv' => '345'
                    )
                )
            )
        );
        echo '<strong>Payment created:</strong>' . "\n";
        echo _toHTML($oNewPayment);
    } catch (\Payname\Exception $e) {
        echo $e . "\n";
    }
}


/* 2. 3DS Validation */

if (
    !isset($_POST['PaRes'])
    and isset($oNewPayment)
    and (in_array($oNewPayment->status, ['W_3DS', 'W_SENDING']))
) :
?>
<h2>3DS Validation</h2>
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
         value="http://<?php echo $_SERVER['SERVER_NAME']; ?>/examples/payment_card.php"
         >
  <input type="submit" value="Test 3DS"><br>
</form>
<?php
endif;


// Refresh payment from API, to be sure
$oPayment = Payment::get($order);


/* 3. After 3DS - Confirmation */

if (isset($_POST['PaRes']) and $oPayment->status == 'W_3DS') {
    echo '<h2>Finalization 3DS</h2>';
    $oPayment->finalize_3DS($_POST['PaRes'], $_POST['MD']);

    echo '<p> Result: </p>';
    echo _toHTML($oPayment = Payment::get($order));
}


/* 4. Payment - END */

if ($oPayment->status == 'C_WAITING') {
    echo '<h2>Payment confirmation</h2>';
    $oPayment->confirm();

    echo '<p> Result: </p>';
    echo _toHTML($oPayment = Payment::get($order));
}
?>

</body>
</html>
