<?php

require 'response.php';
//require 'config.php';
require __DIR__ . '/vendor/autoload.php';
include_once(__DIR__."../../../toplearnr/Includes/Initialize.php");

use PayPal\Api\CreditCard;
use tpl_conf\Conf\ConfigLoader;
use PHPUnit\Framework\TestCase;
$tplConf = ConfigLoader::getTplConfig();
$_SERVER["DOCUMENT_ROOT"] = $tplConf["PROJECT_ROOT"];

class PaypalTest extends TestCase
{
    public function testIfTransactionSucceeded()
    {
		$apiContext = getApiContext('AfXBPdKH63eOOyqCDsgjN0oVSTEw9MHmv2I1Gg4ZclzK6xWGaa5yVSM1X_3Otg-I5Oz7xIefBTT09dcM', 'ENZ78sGnyEHiVoyPF1hAuKeaMDP8cYWxCxUGDUmcHtBkq_e98Uvcxxl2gLF9YkgBqvIHIHWtznTbPKkp', true);

		$card = new CreditCard();
		$card->setNumber('4111111111111111');
		$card->setType('visa');
		$card->setExpireMonth('12');
		$card->setExpireYear('2026');
		$card->setCvv2('123');
		$card->setFirstName('Manish');
		$card->setLastName('Rana');

		$return = $card->create($apiContext);
		print_r($return);

	    //echo "test";
        $ret = Response::gatewayData($apiContext);
        $this->assertSame('approved', $ret['payment_status']);
	}

    public function teardown() :void
	{
		parent::teardown();
	}
}
