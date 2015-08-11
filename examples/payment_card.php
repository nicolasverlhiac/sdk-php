<html>
<head>
<meta charset="utf-8"/>
<title>Example > Payment with card - Payname SDK</title>
</head>
<body>
<pre>
<h1>PAYMENT WITH CARD</h1>
<?php

require_once('../src/Payname/Payname.class.php');
require_once('../src/Payname/Payment/Payment.class.php');
use \Payname\Payment\Payment;

$order_id = 'TCARD'; // todo : un formulaire pour saisir dans la page ?
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
                        , 'order_id' => $order_id
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
        var_dump($oNewPayment);
    } catch (\Payname\Exception $e) {
        echo $e . "\n";
    }
}


/* 2. 3DS Validation */

if (
    !isset($_POST['PaRes'])
    and isset($oNewPayment)
    and ($oNewPayment->status == 'W_3DS'))
    :
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


$oPayment = Payment::get($order_id);


/* 3. After 3DS - Confirmation */

if (isset($_POST['PaRes']) and $oPayment->status == 'W_3DS') {
    echo '<h2>Finalization 3DS</h2>';
    $oPayment->finalize_3DS($_POST['PaRes'], $_POST['MD']);

    echo '<p> Result: </p>';
    var_dump($oPayment = Payment::get($order_id));
}


/* 4. Payment - END */

if ($oPayment->status == 'C_WAITING') {
    echo '<h2>Payment confirmation</h2>';
    $oPayment->confirm();
    
    echo '<p> Result: </p>';
    var_dump($oPayment = Payment::get($order_id));
}


/* 5. END */

echo '<h2>Finally...</h2>';
var_dump($oPayment);
?>

</pre>
</body>
</html>
