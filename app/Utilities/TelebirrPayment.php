<?php

namespace App\Utilities;

use App\Models\AssignFeeMaster;
use App\Services\Models\TelebirrService;
use App\Models\PaymentTransaction;

class TelebirrPayment
{
    protected $telebirrService;

    public function __construct(TelebirrService $telebirrService)
    {
        $this->telebirrService = $telebirrService;
    }

    public function process(AssignFeeMaster $assignFeeMaster, array $paymentData)
    {
        $transaction = $this->startCheckout($assignFeeMaster, $paymentData);

        if (!$transaction) {
            return response()->json(['error' => 'Failed to generate payment URL.'], 400);
        }

        return $transaction;
    }

    public function startCheckout($assignFeeMaster, $paymentData)
    {    
        //Create order
        $orderResponse = $this->createOrder($assignFeeMaster, $paymentData);
        if (!$orderResponse) {
            return response()->json(['error' => 'Order creation failed'], 400);
        }

        //Prapre payment Url
        $prepayId = $orderResponse['biz_content']['prepay_id'];
        $rawRequest = $this->generateRawRequest($prepayId);

        //Payment order information - Reconciliation
        // if ($rawRequest) {
        //     $transaction = new PaymentTransaction();
        //     $transaction->assign_fee_master_id = $assignFeeMaster->id;
        //     $transaction->session_id = $orderResponse['biz_content']['merch_order_id'];
        //     $transaction->status = 'pending';
        //     $transaction->payment_method = 'Telebirr';
        //     $transaction->payment_data = json_encode($paymentData);
        //     $transaction->save();
        // } else {
        //     return response()->json(['error' => 'Failed to process the payment'], 400);
        // }

        $assembled_url = 'https://developerportal.ethiotelebirr.et:38443/payment/web/paygate?' . $rawRequest;

        return $assembled_url;
    }

    public function createOrder($assignFeeMaster, $paymentData)
    {
        // Data Preparation for the payment
        $baseAmount = (float) $paymentData['amount'] + (float) $paymentData['fine_amount'] - (float) ($paymentData['discount_amount'] ?? 0);

        $commissionAmount = 0;
        if (isCommissionFromPayer($assignFeeMaster->company->id)) {
            $commissionAmount = calculateCommission($baseAmount, $assignFeeMaster->company->id);
        }
        $amount = $baseAmount + $commissionAmount;
        $title = $assignFeeMaster->feeMaster->feeType->name;
        $studentId = $assignFeeMaster->student_id;

        $orderResponse = $this->telebirrService->createOrder($title, $amount, $studentId);

        if (!$orderResponse) {
            return response()->json(['error' => 'Order creation failed'], 400);
        }

        return $orderResponse;
    }

    public function generateRawRequest($prepayId)
    {
        $params = [
            'appid' => env('TELEBIRR_MERCHANT_APP_ID'),
            'merch_code' =>  env('TELEBIRR_MERCHANT_CODE'),
            'nonce_str' => bin2hex(random_bytes(16)),
            'prepay_id' => $prepayId,
            'sign_type' => 'SHA256WithRSA',
            'timestamp' => now()->timestamp,
        ];

        // Add the signature
        $params['sign'] = $this->telebirrService->signPayload($params);

        //Construct the raw request string
        $queryString = http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        if (empty($queryString)) {
            return null;
        }
    
        return $queryString ."&version=1.0&trade_type=Checkout";
    }
}          