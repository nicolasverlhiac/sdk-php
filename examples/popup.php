<html>
<head>
<meta charset="utf-8"/>
<title>POPUP TEST</title>
<style type="text/css">
body {
    font-family: 'sans-serif';
}
</style>
</head>
<body>
<h1>Test de popup</h1>

<script type="text/javascript">
function popupwindow(url, title, w, h, closeCallback) {
    // Fixes dual-screen position
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=no, resizable=no, menubar=no, toolbar=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }

    // listen newWindow clode
    var interval = window.setInterval(function() {
        try {
            if (newWindow == null || newWindow.closed) {
                window.clearInterval(interval);
                closeCallback(newWindow);
            }
        } catch (e) {}
    }, 1000);
    return newWindow;
}

</script>

<pre>
<?php

require_once('../src/Payname/Popup/Popup.class.php');

$sUrl='';
try {
    $sUrl = \Payname\Popup\Popup::create(
        array(
            'amount' => 0.99
            , 'callback_ok' => 'http://sdk-php.local/examples/popup.php?ok=true'
            , 'callback_cancel' => 'http://sdk-php.local/examples/popup.php?ok=false'
        )
    );
} catch (\Payname\Exception $e) {
    //var_dump($e);
}

?>

</pre>
<a href="<?php echo $sUrl; ?>" onclick="event.preventDefault(); popupwindow('<?php echo $sUrl;?>', 'Paiement', 300, 320, function() {console.log('closed');});">[Payer]</a>
</body>
</html>
