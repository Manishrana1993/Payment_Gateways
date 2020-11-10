<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

require 'vendor/autoload.php';

$enableSandbox = true;

$paypalConfig = [
    'client_id' => 'AfXBPdKH63eOOyqCDsgjN0oVSTEw9MHmv2I1Gg4ZclzK6xWGaa5yVSM1X_3Otg-I5Oz7xIefBTT09dcM',
    'client_secret' => 'ENZ78sGnyEHiVoyPF1hAuKeaMDP8cYWxCxUGDUmcHtBkq_e98Uvcxxl2gLF9YkgBqvIHIHWtznTbPKkp',
    'return_url' => 'http://localhost/payment/paypal/response.php',
    'cancel_url' => 'http://localhost/payment/paypal/payment-cancelled.html'
];


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
$apiContext = getApiContext($paypalConfig['client_id'], $paypalConfig['client_secret'], $enableSandbox);

/**
 * Set up a connection to the API
 *
 * @param string $clientId
 * @param string $clientSecret
 * @param bool   $enableSandbox Sandbox mode toggle, true for test payments
 * @return \PayPal\Rest\ApiContext
 */
function getApiContext($clientId, $clientSecret, $enableSandbox = false)
{
    $apiContext = new ApiContext(
        new OAuthTokenCredential($clientId, $clientSecret)
    );

    $apiContext->setConfig([
        'mode' => $enableSandbox ? 'sandbox' : 'live'
    ]);

    return $apiContext;
}
