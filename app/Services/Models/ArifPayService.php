<?php

namespace App\Services\Models;

use App\Models\ArifPayTransactionDetail;
use App\Models\GdnDetail;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

use Arifpay\Arifpay\Arifpay;
use Arifpay\Arifpay\Helper\ArifpaySupport;
use Arifpay\Arifpay\Lib\ArifpayBeneficary;
use Arifpay\Arifpay\Lib\ArifpayCheckoutItem;
use Arifpay\Arifpay\Lib\ArifpayCheckoutRequest;
use Arifpay\Arifpay\Lib\ArifpayOptions;

use Illuminate\Support\Facades\Mail;
use App\Mail\SampleEmail;

class ArifPayService
{
    private $requiredFields = [ 
        'cancelUrl', 
        'successUrl', 
        'errorUrl', 
        'notifyUrl', 
        'paymentMethods', 
        'items', 
        'phone', 
    ]; 

    public function calculateBeneficiariesAmount($items) 
    { 
        return collect($items)->reduce(function ($total, $item) { 
            $item['quantity'] = (int) $item['quantity']; 
            $item['price'] = (float) $item['price']; 
            return $total + $item['quantity'] * $item['price']; 
        }, 0); 
    } 
    
    public function validatePaymentInfo($payment_info)
    {
        $missingFields = array_filter($this->requiredFields, function ($field) use ($payment_info) { 
            return !array_key_exists($field, $payment_info); 
        }); 
         
        if (!empty($missingFields)) { 
            throw new \Exception('The following required fields are missing from payment_info: ' . implode(', ', $missingFields)); 
        } 
              
        if (!array_key_exists('beneficiaries', $payment_info)) { 
            $beneficiariesAmount = $this->calculateBeneficiariesAmount($payment_info['items']); 
            $payment_info['beneficiaries'] = [ 
                [ 
                    'accountNumber' => '01320811436100', 
                    'bank' => 'AWINETAA', 
                    'amount' => $beneficiariesAmount, 
                ], 
            ]; 
        } 

        return $payment_info;
    }

    public function makePayment($id, $api_key)
    {  
        $gdnDetail = GdnDetail::findorfail($id);
        
        $expired = ArifpaySupport::getExpireDateFromDate(Carbon::now()->addWeek());

        try {
            $payment_info = [
                "cancelUrl" => route ('CanceledSession', ['routeId' => $gdnDetail->gdn_id]),
                "phone" => "0933624757",
                "email" => "mikiyasleul@gmail.com",
                "errorUrl" => "http://error.com",
                "notifyUrl" =>  route('callback'),
                "successUrl" =>  route ('successSession', ['routeId' => $gdnDetail->gdn_id]),
                "paymentMethods" => ["TELEBIRR_USSD"],
                'items' => [
                    [
                        'name' => $gdnDetail->product->name,
                        'quantity' => (int) $gdnDetail->quantity,
                        'price' => (float) $gdnDetail->unit_price,
                        'description' => 'Product Description',
                        'image' => 'https://4.imimg.com/data4/KK/KK/GLADMIN-/product-8789_bananas_golden-500x500.jpg',
                    ],
                ],
                'lang' => 'EN',
                'nonce' => floor(rand() * 10000) . "",
                "expireDate" => $expired
            ];

            $payload = $this->validatePaymentInfo($payment_info);

            $client = new Client();
    
            $response = $client->post('https://gateway.arifpay.net/api/checkout/session', [
                'headers' => [
                    'x-arifpay-key' =>  $api_key,
                    'Content-Type' => 'application/json',
                    'Accepts' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['data']['paymentUrl'])) {
                $paymentUrl = $data['data']['paymentUrl'];
               
                return redirect($paymentUrl);
            } else {
                return response()->json(['error' => 'Payment URL not found'], 500);
            }
        } catch (\Exception $e) {
            return $e;
            return response()->json(['error' => 'Error creating checkout session'], 500);
        }   
    }

    public function callback($request, $hahu_api_key, $hahu_device_id)
    {
        Log::info('ArifPay Notification received', ['data' => $request->all()]);

        $transactionStatus = $request['transaction.transactionStatus'];

        if ($transactionStatus === 'SUCCESS') {
                ArifPayTransactionDetail::create([
                    'gdn_detail_id' => $gdn_detail_id,
                    'transaction_status' => $transactionStatus,
                    'session_id_number' => $request->id,
                    'notification_url' => $request->notificationUrl,
                    'uuid' => $request->uuid,
                    'nonce' => $request->nonce,
                    'phone' => $request->phone,
                    'total_amount' => $request->totalAmount,
                    'transaction_id' => $request->transaction.transactionId,
                ]);

            $this->sendSms($request, $hahu_api_key, $hahu_device_id);

            $this->sendEmail();

            return response()->json(['status' => 'success'], 200);
        }
        elseif ($transactionStatus === 'FAILED') {
            return response()->json(['message' => 'Transaction failed'], 200);
        } elseif ($transactionStatus === 'CANCELLED') {
            return response()->json(['message' => 'Transaction cancelled'], 200);
        } else {
            return response()->json(['message' => 'Transaction pending or unknown status'], 200);
        }
    }

    public function sendSms($reqeust, $hahu_api_key, $hahu_device_id)
    {
        $downloadLink = route('recipt', ['transactionId' => 'AA56C69DD8FF']);

        $message = [
            "secret" => $hahu_api_key,
            "mode" => "devices",
            "device" => $hahu_device_id,
            "sim" => 1,
            "priority" => 1,
            "phone" => '+251943413094',
            "message" => "Dear arifSchool Customer". 
                         ".\nTo download your payment information, please click this link: " . $downloadLink . 
                         "\nThank You for choosing Us !",                   
        ];

        $response = $this->sendCurlRequest("https://hahu.io/api/send/sms", $message);

        $result = json_decode($response, true);

        return response()->json($result);   
    }

    private function sendCurlRequest($url, $data)
    {
        $cURL = curl_init($url);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($cURL);
        curl_close($cURL);

        return $response;
    }
    
    public function ariPayRecipt($transactionId)
    {
        $arifPayRecipt = ArifPayTransactionDetail::where('transaction_id', $transactionId)->first();

        $arifPayRecipt->load(['gdnDetail']);

        return Pdf::loadView('arifpay-transactions.print', compact('arifPayRecipt'))->stream();
    }

    public function sendEmail() 
    {
        $recipient = 'mlrdeveloper28@gmail.com';
        
        $data = [
            'name' => 'Mikiyas Leul', 
            'amount' => 200, 
            'paymentDate' => Carbon::now(),
            'transactionId' => 'AA56C69DD8FF', 
            'schoolYear' => Carbon::now()->year,  
            'schoolName' => 'PrimeBridge School',
            'Message' => 'Thank you for your prompt payment and for being a valued part of our school community.',
            'link' => route('recipt', ['transactionId' => 'AA56C69DD8FF']),
        ];

        Mail::to($recipient)->send( new SampleEmail(
                                    $data['name'], $data['amount'], $data['paymentDate'], $data['transactionId'],
                                    $data['schoolYear'], $data['schoolName'], $data['Message'], $data['link']));

        return response()->json(['message' => 'Email sent successfully to ' . $recipient]);
    }
}