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
<pre>
<?php

require_once('../src/Payname/Popup/Popup.class.php');

$sUrl='';
try {
    $sUrl = \Payname\Popup\Popup::create(0.99);
} catch (\Payname\Exception $e) {
    var_dump($e);
}

?>

</pre>
<a href="<?php echo $sUrl?>" target="_blank">[Payer]</a>
</body>
</html>
