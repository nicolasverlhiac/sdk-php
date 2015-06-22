<html>
<head>
<meta charset="utf-8"/>
<title>SDK TEST - USER</title>
</head>
<body>
<pre>
<h1>USER EXAMPLE</h1>

<?php

require_once('../src/Payname/Payname.class.php');
require_once('../src/Payname/User/User.class.php');
require_once('../src/Payname/User/Doc.class.php');
require_once('../src/Payname/User/Iban.class.php');



// -----------------------------------------------------------------------------
// USER
// -----------------------------------------------------------------------------

echo '<h1>USER</h1>';
use \Payname\User\User;


/* CREATE */

echo '<h2>CREATE new user</h2>';
try {
    $oNewUser = User::create(
        array(
            'email' => 'test42@test.te'
            , 'phone' => '0123456789'
            , 'first_name' => 'Test'
            , 'last_name' => 'TEST'
        )
    );
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'User created: ' . "\n";
var_dump($oNewUser);


/* READ */

echo '<h2>READ instanciate existing user</h2>';
try {
    $oOldUser = User::get('OuAIb');
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'User instanciated: ' . "\n";
var_dump($oOldUser);


/* LIST */

echo '<h2>LIST</h2>';
try {
    $aUsers = User::getAll();
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'Users trouves : ' . count($aUsers) . "\n";
echo 'Premiers elements : ' . print_r(array_slice($aUsers, 0,5), true) . "\n";


/* UPDATE */

echo '<h2>UPDATE existing user</h2>';
$oOldUser->first_name = 'Test';
try {
    $oOldUser->update();
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'User after save: ' . "\n";
var_dump(User::get($oOldUser->hash));


/* DELETE */

echo '<h2>DELETE existing user</h2>';
try {
    $oNewUser->delete();
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}



// -----------------------------------------------------------------------------
// USER -> DOC
// -----------------------------------------------------------------------------

echo '<h1>USER -> DOC</h1>';
use \Payname\User\Doc;


/* CREATE */

echo '<h2>CREATE</h2>';

try {
    $oNewDoc = Doc::create(
        array(
            'user' => 'OuAIb'
            , 'type' => 'home'
            , 'file' => 'data:image/png;base64,OOOOOO'
        )
    );
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'Doc created: ' . "\n";
var_dump($oNewDoc);


/* READ */

echo '<h2>READ</h2>';
try {
    $oOldDoc = Doc::get('OuAIb', 'DWyq6');
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'Doc instanciated: ' . "\n";
var_dump($oOldDoc);


/* READ FROM USER */

echo '<h2>READ from existing user</h2>';
try {
    $oUserDoc = $oOldUser->doc('DWyq6');
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'Doc instanciated: ' . "\n";
var_dump($oUserDoc);


/* LIST */

echo '<h2>LIST</h2>';
try {
    $aDocs = Doc::getAll('OuAIb');
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'Docs trouves : ' . count($aDocs) . "\n"
    . print_r($aDocs, true) . "\n";


/* LIST FROM USER */

echo '<h2>LIST from existing user</h2>';
try {
    $aUserDocs = $oOldUser->doc();
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'Docs trouves : ' . count($aUserDocs) . "\n"
    . print_r($aUserDocs, true) . "\n";


/* UPDATE */

echo '<h2>UPDATE</h2>';
echo '<em>no update, use create instead</em>';


/* DELETE */

echo '<h2>DELETE</h2>';
try {
    $oNewDoc->delete();
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}



// -----------------------------------------------------------------------------
// USER -> IBAN
// -----------------------------------------------------------------------------

echo '<h1>USER -> IBAN</h1>';
use \Payname\User\Iban;


/* CREATE */

echo '<h2>CREATE</h2>';
try {
    $oNewIban = Iban::create(
        array(
            'user' => 'OuAIb'
            , 'iban' => 'FR6577698116285976981637216'
            , 'isDefault' => 'false'
        )
    );
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'Iban created: ' . "\n";
var_dump($oNewIban);


/* READ */

echo '<h2>READ</h2>';
try {
    $oOldIban = Iban::get('OuAIb', '6KJXU');
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'Iban instanciated: ' . "\n";
var_dump($oOldIban);


/* READ FROM USER */

echo '<h2>READ from existing user</h2>';
try {
    $oUserIban = $oOldUser->iban('6KJXU');
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'Iban instanciated: ' . "\n";
var_dump($oUserIban);


/* LIST */

echo '<h2>LIST</h2>';
$aIbans = Iban::getAll('OuAIb');
echo 'Ibans trouves : ' . count($aIbans) . "\n"
    . print_r($aIbans, true) . "\n";


/* LIST FROM USER */

echo '<h2>LIST from existing user</h2>';
try {
    $aUserIbans = $oOldUser->iban();
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'IBANs trouves : ' . count($aUserIbans) . "\n"
    . print_r($aUserIbans, true) . "\n";


/* UPDATE */

echo '<h2>UPDATE</h2>';
$oNewIban->iban = 'FR8422370482212494818262359';
$oNewIban->is_default = 'true';
try {
    $oNewIban->update();
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}
echo 'Updated IBAN: ' . "\n";
var_dump(Iban::get($oNewIban->user, $oNewIban->hash));


/* DELETE */

echo '<h2>DELETE</h2>';
try {
    $oNewIban->delete();
} catch (\Payname\Exception $e) {
    echo $e . "\n";
}


?>
</pre>
</body>
</html>
