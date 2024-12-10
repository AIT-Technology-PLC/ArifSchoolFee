<?php

namespace App\Utilities;

use App\Models\AssignFeeMaster;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Exception;

class ArifPayPayment
{
    private $apiKey;
    private $arifpayUrl;
    private $notifyUrl;
    private $requiredFields = [
        'cancelUrl', 'successUrl', 'errorUrl', 'notifyUrl', 'paymentMethods', 'phone', 'email', 'items', 'lang', 'nonce', 'expireDate'
    ];

    public function __construct()
    {
        $this->apiKey = env('ARIFPAY_API_KEY');
        $this->arifpayUrl = env('ARIFPAY_CHECKOUT_URL');
        $this->notifyUrl = route('arifpay.callback');
    }

    public function process(AssignFeeMaster $assignFeeMaster, array $paymentData)
    {
        try {
            $payload = $this->preparePaymentPayload($assignFeeMaster, $paymentData);

            $validatedPaymentInfo = $this->validatePaymentInfo($payload);

            $response = $this->makeApiCall($validatedPaymentInfo);

            if (isset($response['data']['sessionId'])) {
                $transaction = new PaymentTransaction();
                $transaction->assign_fee_master_id = $assignFeeMaster->id;
                $transaction->session_id = $response['data']['sessionId'];
                $transaction->status = 'pending';
                $transaction->payment_method = 'Arifpay';
                $transaction->payment_data = json_encode($paymentData);
                $transaction->save();
            }

            if (isset($response['data']['paymentUrl'])) {
                return $response['data']['paymentUrl'];
            } else {
                throw new Exception('Error: Payment URL not received from ArifPay.');
            }
        } catch (Exception $e) {
            Log::error('ArifPay Payment Error: ' . $e->getMessage());
            throw $e; 
        }
    }

    private function preparePaymentPayload(AssignFeeMaster $assignFeeMaster, array $paymentData)
    {
        return [
            'cancelUrl' => route('arifpay.cancel', ['routeId' => $assignFeeMaster->student_id]),
            'successUrl' => route('arifpay.success', ['routeId' => $assignFeeMaster->student_id]),
            'errorUrl' => route('arifpay.error', ['routeId' => $assignFeeMaster->student_id]),
            'notifyUrl' => $this->notifyUrl,
            'paymentMethods' => ['TELEBIRR_USSD'],
            'phone' => $assignFeeMaster->student->phone ?? '0933624757',
            'email' => $assignFeeMaster->student->email ?? 'student@example.com',
            'items' => [
                [
                    'name' => $assignFeeMaster->feeMaster->feeType->name,
                    'quantity' => 1,
                    'price' => (float) $paymentData['amount'] + (float) $paymentData['fine_amount'] - (float) ($paymentData['discount_amount'] ?? 0),
                    'description' => 'Fee Payment',
                    'image' => 'https://thumbs.dreamstime.com/z/pay-application-fee-isolated-cartoon-vector-illustrations-young-girl-makes-payment-college-education-online-using-gold-card-256581225.jpg?w=768',
                ],
            ],
            'lang' => 'EN',
            'nonce' => floor(rand() * 10000) . "",
            'expireDate' => Carbon::now()->addWeek(),
        ];
    }

    public function validatePaymentInfo($paymentInfo)
    {
        $missingFields = array_filter($this->requiredFields, function ($field) use ($paymentInfo) {
            return !array_key_exists($field, $paymentInfo);
        });

        if (!empty($missingFields)) {
            throw new \Exception('The following required fields are missing from paymentInfo: ' . implode(', ', $missingFields));
        }

        if (!array_key_exists('beneficiaries', $paymentInfo)) {
            $beneficiariesAmount = $this->calculateBeneficiariesAmount($paymentInfo['items']);
            $paymentInfo['beneficiaries'] = [
                [
                    'accountNumber' => '01320811436100',
                    'bank' => 'AWINETAA',
                    'amount' => $beneficiariesAmount,
                ],
            ];
        }

        return $paymentInfo;
    }

    public function calculateBeneficiariesAmount($items)
    {
        return collect($items)->reduce(function ($total, $item) {
            $item['quantity'] = (int) $item['quantity'];
            $item['price'] = (float) $item['price'];
            return $total + $item['quantity'] * $item['price'];
        }, 0);
    }

    private function makeApiCall(array $payload)
    {
        $response = Http::withHeaders([
            'x-arifpay-key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accepts' => 'application/json',
        ])->post($this->arifpayUrl, $payload);

        if ($response->successful()) {
            return $response->json();
        } else {
            throw new Exception('Error creating checkout session with ArifPay.');
        }
    }
}
