<?php
require_once('vendor/autoload.php');

$stripe = [
  "secret_key"      => "sk_test_51HYuScCESEr6sBweRExOsyUTbeWubL3wS1IqblQ69GeHGeYBjEBWzuG9xgryyBQHG2EAeCe4cgCV926vdXnkAc5t007atUFfgr",
  "publishable_key" => "pk_test_51HYuScCESEr6sBwevd2fr3UI2xyvQT02oADoXjb7soTJstgwnEiEU6YkR0J9HRfMoGo66JXY6mKP8uAh1nILPGfp009VD14iA8",
];

\Stripe\Stripe::setApiKey($stripe['secret_key']);

function dbConnect()
{
    $db = new mysqli('localhost', 'root', '', 'tpl_payment');

    if ($db->connect_errno)
    {
        die("Connect failed: ". $db->connect_error);
    }
    else{
        return $db;
    }
}
$db = dbConnect();
?>