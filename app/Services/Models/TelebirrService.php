<?php

namespace App\Services\Models;
use Illuminate\Support\Facades\Http;
use phpseclib3\Crypt\RSA;

class TelebirrService
{
    protected $baseUrl;
    protected $fabricAppId;
    protected $appSecret;
    protected $merchantAppId;
    protected $privateKey;
    protected $merchantCode;

    public function __construct()
    {
        $this->baseUrl = env('TELEBIRR_BASE_URL');
        $this->fabricAppId = env('TELEBIRR_FABRIC_APP_ID');
        $this->appSecret = env('TELEBIRR_APP_SECRET');
        $this->merchantAppId = env('TELEBIRR_MERCHANT_APP_ID');
        $this->privateKey = env('TELEBIRR_PRIVATE_KEY');
        $this->merchantCode = env('TELEBIRR_MERCHANT_CODE');
    }

    public function applyFabricToken()
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-APP-Key' => $this->fabricAppId,
            ])->post($this->baseUrl . '/payment/v1/token', [ 'appSecret' => $this->appSecret ]);

            if ($response->successful()) {
                return $response->json('token');
            } 
        }
        catch (\Exception $e) {
            throw new \Exception('Failed to apply fabric token: ' . $e->getMessage());
        }
    }

    public function createOrder($title, $amount, $studentId)
    {
        $fabricToken = $this->applyFabricToken();

        if (!$fabricToken) {
            return null;
        }

        $payload = $this->createRequestObject($title, $amount, $studentId);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-APP-Key' => $this->fabricAppId,
                'Authorization' => $fabricToken,
            ])->post($this->baseUrl . '/payment/v1/merchant/preOrder', $payload);

            if ($response->successful()) {
                return $response->json();
            }
        }
        catch (\Exception $e) {
            return null;
            throw new \Exception('Failed to create order: ' . $e->getMessage());
        }
    }

    public function createRequestObject($title, $amount, $studentId)
    {
        $payload = [
            'timestamp' =>  (string) time(),
            'nonce_str' => bin2hex(random_bytes(16)),
            'method' => 'payment.preorder',
            'version' => '1.0',
            'biz_content' => [
                'notify_url' => route('telebirr.notify'),
                'appid' => $this->merchantAppId,
                'merch_code' => $this->merchantCode,
                'merch_order_id' => (string) time(),
                'trade_type' => 'Checkout',
                'title' => $title,
                'total_amount' => (string) $amount,
                'trans_currency' => 'ETB',
                'timeout_express' => '45m',
                'business_type' => 'BuyGoods',
                'payee_identifier' => $this->merchantCode,
                'payee_identifier_type' => '04',
                'payee_type'=> '5000',
                'redirect_url' => route('telebirr.redirect', ['routeId' => $studentId]),
                'callback_info' => 'From web',
            ],
            'sign_type' => 'SHA256WithRSA',
        ];

        $payload['sign'] = $this->signPayload($payload);

        return $payload;
    }

    public function signPayload($request)
    {
        $excludeFields = ["sign", "sign_type", "header", "refund_info", "openType", "raw_request"];
        $join = [];

        foreach ($request as $key => $value) {
            if (in_array($key, $excludeFields)) {
                continue;
            }
            if ($key == "biz_content") {
                $bizContent = $request["biz_content"];
                foreach ($bizContent as $k => $v) {
                    $join[] = $k . "=" . $v;
                }
            } else {
                $join[] = $key . "=" . $value;
            }
        }

        $array = [
            "join" => $join
        ];

        sort($join);

        $array["sortedJoin"] = $join;

        $separator = '&';
        $inputString = implode($separator, $join);

        $key =  "-----BEGIN PRIVATE KEY-----\n$this->privateKey\n-----END PRIVATE KEY-----";

        try {
            $rsa = RSA::loadPrivateKey($key);
    
            $signature = $rsa->sign($inputString);
    
            if ($signature === false) {
                throw new \Exception('Failed to sign the payload');
            }
    
            return base64_encode($signature);
        } catch (\Exception $e) {
            throw new \Exception('Failed to load private key or sign payload: ' . $e->getMessage());
        }
    }
}