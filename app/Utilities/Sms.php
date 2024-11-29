<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Exception;

class Sms
{
    public static function sendSingleMessage(String $phoneNumber, String $message)
    {
        if (empty($phoneNumber) || empty($message)) {
            throw new InvalidArgumentException('Phone number and message are required.');
        }

        $data = [
            'to' => $phoneNumber,
            'from' => env('AFROMESSAGE_FROM', ''),
            'sender' => env('AFROMESSAGE_SENDER',''),
            'message' => strip_tags($message),
            'callback' => env('AFROMESSAGE_CALLBACK', '')
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('AFROMESSAGE_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post(env('AFROMESSAGE_SINGLE_MESSAGE_URL'), $data);

        if ($response->successful()) {
            $responseData = $response->json();

            if ($responseData['acknowledge'] === 'success') {
                return $responseData;
            } else {
                throw new Exception('Failed to send SMS: ' . json_encode($responseData['response']['errors']));
            }
        } else {
            throw new Exception('Failed to send SMS: ' . $response->body());
        }
    }

    public static function sendBulkMessage(array $phoneNumbers, String $message)
    {
        if (empty($phoneNumbers) || empty($message)) {
            throw new InvalidArgumentException('Phone numbers and message are required.');
        }

        $data = [
            'to' => $phoneNumbers,
            'message' => strip_tags($message),
            'from' => env('AFROMESSAGE_FROM',''),
            'sender' => env('AFROMESSAGE_SENDER', ''),
            'campaign' => env('AFROMESSAGE_CAMPAIGN_NAME', ''),
            'createCallback'=> env('AFROMESSAGE_CREATE_CALLBACK', ''),
            'statusCallback'=> env('AFROMESSAGE_STATUS_CALLBACK', '')            
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('AFROMESSAGE_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post(env('AFROMESSAGE_BULK_MESSAGE_URL'), $data);

        if ($response->successful()) {
            $responseData = $response->json();
    
            if ($responseData['acknowledge'] === 'success') {
                return $responseData;
            } else {
                throw new Exception('Failed to send bulk SMS: ' . json_encode($responseData['response']['errors']));
            }
        } else {
            throw new Exception('Failed to send bulk SMS: ' . $response->body());
        }
    }

    public static function sendSecurityCode(String $phoneNumber, string $messagePrefix, string $messagePostfix)
    {
        if (empty($phoneNumber) || empty($messagePrefix) || empty($messagePostfix)) {
            throw new InvalidArgumentException('Phone number, message prefix, and message postfix are required.');
        }

        $pre = urlencode($messagePrefix);
        $post = urlencode($messagePostfix);
        
        $data = [
            'from' => env('AFROMESSAGE_FROM', ''),
            'sender' => env('AFROMESSAGE_SENDER', ''),
            'to' => $phoneNumber,
            'pre' => $pre, 
            'post' => $post, 
            'sb' => env('SPACES_BEFORE', ''),
            'sa' => env('SPACES_AFTER', ''),
            'ttl' => env('TIME_TO_LIVE', ''),
            'len' => env('CODE_LENGTH', ''),
            't' => env('CODE_TYPE', ''),
            'callback' => env('AFROMESSAGE_CALLBACK', '')
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('AFROMESSAGE_TOKEN'),
            'Content-Type' => 'application/json',
        ])->get(env('AFROMESSAGE_SECURITY_MESSAGE_URL'), $data);

        if ($response->successful()) {
            $responseData = $response->json();
    
            if ($responseData['acknowledge'] === 'success') {
                return $responseData;
            } else {
                throw new Exception('Failed to send security code: ' . json_encode($responseData['response']['errors']));
            }
        } else {
            throw new Exception('Failed to send security code: ' . $response->body());
        }
    }
}