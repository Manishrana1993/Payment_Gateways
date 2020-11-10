<?php
  require_once('./config.php');
  require_once('./index.php');

class StripeResponse
{
  public static function CreateCharge()
  {
    $token  = $_POST['stripeToken'];
    $email  = $_POST['stripeEmail'];
    $customer = \Stripe\Customer::create([
        'email' => $email,
        'source'  => $token,
    ]);

    $charge = \Stripe\Charge::create([
        'customer' => $customer->id,
        'amount'   => 5000,
        'currency' => 'INR',
    ]);
    $paymentResponse = $charge->jsonSerialize();
    if($paymentResponse['status'] == 'succeeded')
    {
      echo "The payment is successful. Transaction ID: {$paymentResponse['balance_transaction']}";
    }
    else
    {
      echo "Payment failed!";
    }
    $paymentResponse = $charge->jsonSerialize();

    print_r($paymentResponse);
    return $paymentResponse;
  }

  public function addTransaction($paymentResponse, $db, $createTime)
  {
    $gatewayTime = gmdate("Y-m-d\TH:i:s\Z", $paymentResponse['created']);
    $puchaseId = $paymentResponse['id'];
    $balanceTransaction = $paymentResponse['balance_transaction'];
    $paidCurrency = json_encode($paymentResponse);
    $success = 1;
    $isPaymentExist = $db->query("SELECT * FROM TransactionDetails WHERE paymentGatewayTransactionId = '".$balanceTransaction."'");
        if($isPaymentExist->num_rows == 0)
        {
          $db->query("INSERT INTO TransactionDetails(gatewayTime, createTime, purchaseId, paymentGatewayTransactionId, transactionDetails, success)VALUES('". $gatewayTime ."', '". $createTime ."', '". $puchaseId ."', '". $balanceTransaction ."', '". $paidCurrency ."', '". $success ."')");
        }
       // echo "Transaction is successful ".$balanceTransaction;
  }

  public static function createPurchase($paymentResponse, $db, $createTime)
  {
    $gatewayTime = gmdate("Y-m-d\TH:i:s\Z", $paymentResponse['created']);
    $lastUpdated = gmdate("Y-m-d\TH:i:s\Z");
    $amountPaid = $paymentResponse['amount'];
    $balanceTransaction = $paymentResponse['balance_transaction'];
    $paidCurrency = $paymentResponse['currency'];
    $paymentGatewayType = 2;
    $success = 1;
    $paymentGatewayData     =   json_encode($paymentResponse);
    $paymentStatus = $paymentResponse['status'];
    $isPaymentExist = $db->query("SELECT * FROM Purchase WHERE paymentGatewayTransactionId = '".$balanceTransaction."'");
				if($isPaymentExist->num_rows == 0)
				{
					$db->query("INSERT INTO Purchase(gatewayTime, createTime, lastUpdated, amount, currency, paymentGatewayType, paymentGatewayData, paymentGatewayTransactionId, success, status)VALUES('". $gatewayTime ."', '". $createTime ."', '". $lastUpdated ."', '". $amountPaid ."', '". $paidCurrency ."', '". $paymentGatewayType ."', '". $paymentGatewayData ."', '". $balanceTransaction ."', '". $success ."', '". $paymentStatus ."')");
				}
  }
  public function confirmPayment()
  {
    $stripe = new \Stripe\StripeClient(
      'sk_test_51HYuScCESEr6sBweRExOsyUTbeWubL3wS1IqblQ69GeHGeYBjEBWzuG9xgryyBQHG2EAeCe4cgCV926vdXnkAc5t007atUFfgr'
    );

    $stripe->paymentIntents->retrieve(
      'pi_1EUq6iJAJfZb9HEB8t3XTccE',
      []
    );

    $stripe->paymentIntents->cancel(
      'pi_1EUq6iJAJfZb9HEB8t3XTccE',
      []
    );

    $stripe->paymentIntents->confirm(
      'pi_1EUq6iJAJfZb9HEB8t3XTccE',
      ['payment_method' => 'pm_card_visa']
    );
  }

  public function listTransaction($startDate, $endDate)
  {
    $List = \Stripe\Charge::all(
      array(
          'limit'   => 100,
          'created' => array(
              'gte' => strtotime($startDate),
              'lte' => strtotime($endDate)
            )
          )
        );
    $transactionList =  json_encode($List);
    //print_r($transactionList);
  }

}
$stripeReponse = new StripeResponse();
$paymentResponse = $stripeReponse->CreateCharge();
/* $startDate = "2020-10-08T11:22:15Z";
$endDate = "2020-10-09T04:00:00Z";
$stripeReponse = new StripeResponse();
$paymentResponse = $stripeReponse->CreateCharge();
$stripeReponse->addTransaction($paymentResponse, $db, $createTime);
$stripeReponse->createPurchase($paymentResponse, $db, $createTime);
$stripeReponse->listTransaction($startDate, $endDate);