<?php

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

require 'config.php';
require 'index.php';
class Response
{
    public static function gatewayData($apiContext)
    {
        if (empty($_GET['paymentId']) || empty($_GET['PayerID']))
        {
        throw new Exception('The response is missing the paymentId and PayerID');
        }

        $paymentId = $_GET['paymentId'];
        $payment = Payment::get($paymentId, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($_GET['PayerID']);
        try {
            // Take the payment
            $payment->execute($execution, $apiContext);

        } catch (Exception $e) {
            echo $e->getCode();
        }
        $payment = Payment::get($paymentId, $apiContext);

        if($payment->getState() == 'approved')
        {
            echo "The payment is successful. Transaction ID: {$payment->getId()}";
        }
        else
        {
            echo "Payment failed!";
        }

        $data = [
            'transaction_id' => $payment->getId(),
            'gatewayTime' => $payment->getCreateTime(),
            'payment_amount' => $payment->transactions[0]->amount->total,
            'payment_status' => $payment->getState(),
            'invoice_id' => $payment->transactions[0]->invoice_number,
            'currency'  =>  $payment->transactions[0]->amount->currency,
            'paymentGatewayType'    =>  1,
            'success'   => 1,
            'purchaseId'    =>  $payment->cart
        ];

        // echo $payment;
        // $responseArray = $payment->jsonSerialize();
        return $data;
    }

    public function addTransaction($data, $db, $createTime)
    {
        print_r($createTime);
        $gatewayTime = $data['gatewayTime'];
        $paymentGatewayTransactionId =   $data['transaction_id'];
        $purchaseId =    $data['purchaseId'];
        $success     =   $data['success'];
        $transactionDetails     =   json_encode($data);
        $isPaymentExist = $db->query("SELECT * FROM TransactionDetails WHERE paymentGatewayTransactionId = '".$paymentGatewayTransactionId."'");
				if($isPaymentExist->num_rows == 0)
				{
					$db->query("INSERT INTO TransactionDetails(gatewayTime, createTime, purchaseId, paymentGatewayTransactionId, transactionDetails, success)VALUES('". $gatewayTime ."', '". $createTime ."', '". $purchaseId ."', '". $paymentGatewayTransactionId ."', '". $transactionDetails ."', '". $success ."')");
				}
    }

    public function createPurchase($data, $db, $createTime)
    {
        $gatewayTime = $data['gatewayTime'];
        $lastUpdated = gmdate("Y-m-d\TH:i:s\Z");
        $paymentGatewayTransactionId =   $data['transaction_id'];
        $amount =    $data['payment_amount'];
        $paymentGatewayType =   $data['paymentGatewayType'];
        $currency     =   $data['currency'];
        $success     =   $data['success'];
        $status     =   $data['payment_status'];
        $paymentGatewayData     =   json_encode($data);
        $isPaymentExist = $db->query("SELECT * FROM Purchase WHERE paymentGatewayTransactionId = '".$paymentGatewayTransactionId."'");
				if($isPaymentExist->num_rows == 0)
				{
					$db->query("INSERT INTO Purchase(gatewayTime, createTime, lastupdated, amount, currency, paymentGatewayType, paymentGatewayData, paymentGatewayTransactionId, success, status)VALUES('". $gatewayTime ."', '". $createTime ."', '". $lastUpdated ."', '". $amount ."', '". $currency ."', '". $paymentGatewayType ."', '". $paymentGatewayData ."', '". $paymentGatewayTransactionId ."', '". $success ."', '". $status ."')");
				}
    }

    public function listPayments($apiContext, $startDate, $endDate)
    {
        try
        {
            $params = array('count' => 10, 'start_time' => strtotime($startDate), 'end_time' => strtotime($endDate));

            $payments = Payment::all($params, $apiContext);
            print_r($payments);
        }
        catch (Exception $ex)
        {
            // exception message
        }
    }
}
$startDate = "2020-09-26T11:27:23Z";
$endDate = "2020-09-28T11:27:23Z";
$response = new Response();
$data = $response->gatewayData($apiContext);
$response->gatewayData($apiContext);
$response->addTransaction($data , $db, $createTime);
$response->createPurchase($data, $db, $createTime);
$response->gatewayData($apiContext);
$response->listPayments($apiContext, $startDate, $endDate);