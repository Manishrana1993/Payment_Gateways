<?php

require 'StripeResponse.php';
//require 'config.php';
require __DIR__ . '/vendor/autoload.php';
include_once(__DIR__."../../../toplearnr/Includes/Initialize.php");

use tpl_conf\Conf\ConfigLoader;
use PHPUnit\Framework\TestCase;
$tplConf = ConfigLoader::getTplConfig();
$_SERVER["DOCUMENT_ROOT"] = $tplConf["PROJECT_ROOT"];

class StripeTest extends TestCase
{
    public function testIfTransactionSucceeded()
    {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51HYuScCESEr6sBweRExOsyUTbeWubL3wS1IqblQ69GeHGeYBjEBWzuG9xgryyBQHG2EAeCe4cgCV926vdXnkAc5t007atUFfgr'
          );
          $retToken =  $stripe->tokens->create([
            'card' => [
              'number' => '4242424242424242',
              'exp_month' => 8,
              'exp_year' => 2021,
              'cvc' => '314',
            ],
          ]);
          print_r($retToken);
        $_POST['stripeToken'] = $retToken->id;
        $_POST['stripeEmail'] = "test@gmail.com";
        $ret = StripeResponse::CreateCharge();
        $this->assertSame('succeeded', $ret['status']);
    }

    public function testIfTransactionNotSucceeded()
    {
      $stripe = new \Stripe\StripeClient(
        'sk_test_51HYuScCESEr6sBweRExOsyUTbeWubL3wS1IqblQ69GeHGeYBjEBWzuG9xgryyBQHG2EAeCe4cgCV926vdXnkAc5t007atUFfgr'
      );
      $retToken =  $stripe->tokens->create([
        'card' => [
          'number' => '4242424242424242',
          'exp_month' => 8,
          'exp_year' => 2021,
          'cvc' => '314',
        ],
      ]);
      print_r($retToken);
      $_POST['stripeToken'] = $retToken->id;
      $_POST['stripeEmail'] = "test@gmail.com";
      $ret = StripeResponse::CreateCharge();
      $this->assertNotSame('succeeded', $ret['status']);
    }

    public function teardown() :void
    {
      parent::teardown();
    }
}
