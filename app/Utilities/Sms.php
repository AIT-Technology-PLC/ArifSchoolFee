<?php

namespace App\Utilities;

use App\Models\SmsSetting;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Exception;

class Sms
{
    private $smsSetting;

    public function __construct(SmsSetting $smsSetting)
    {
        $this->smsSetting = $smsSetting;
    }

    public static function sendSingleMessage(String $phoneNumber, String $message)
    {
        $smsSetting = $this->smsSetting->first();

        if (empty($phoneNumber) || empty($message)) {
            throw new InvalidArgumentException('Phone number and message are required.');
        }

        $data = [
            'to' => $phoneNumber,
            'message' => strip_tags($message),
            'from' => $smsSetting->from ?? env('AFROMESSAGE_FROM', ''),
            'sender' => userCompany()->name ?? env('AFROMESSAGE_SENDER', ''),
            'callback' => $smsSetting->callback ?? env('AFROMESSAGE_CALLBACK', '')
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $smsSetting->token ?? env('AFROMESSAGE_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post($smsSetting->single_message_url ?? env('AFROMESSAGE_SINGLE_MESSAGE_URL'), $data);

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
        $smsSetting = $this->smsSetting->first();

        if (empty($phoneNumbers) || empty($message)) {
            throw new InvalidArgumentException('Phone numbers and message are required.');
        }

        $data = [
            'to' => $phoneNumbers,
            'message' => strip_tags($message),
            'from' => $smsSetting->from ?? env('AFROMESSAGE_FROM',''),
            'sender' => userCompany()->name ?? env('AFROMESSAGE_SENDER', ''),
            'campaign' => $smsSetting->campaign ?? env('AFROMESSAGE_CAMPAIGN_NAME', ''),
            'createCallback'=> $smsSetting->create_callback ?? env('AFROMESSAGE_CREATE_CALLBACK', ''),
            'statusCallback'=> $smsSetting->status_callback ?? env('AFROMESSAGE_STATUS_CALLBACK', '')            
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $smsSetting->token ?? env('AFROMESSAGE_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post($smsSetting->bulk_message_url ?? env('AFROMESSAGE_BULK_MESSAGE_URL'), $data);

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
        $smsSetting = $this->smsSetting->first();

        if (empty($phoneNumber) || empty($messagePrefix) || empty($messagePostfix)) {
            throw new InvalidArgumentException('Phone number, message prefix, and message postfix are required.');
        }

        // URL encode message prefix and postfix
        $pre = urlencode($messagePrefix ?? $smsSetting->message_prefix);
        $post = urlencode($messagePostfix ?? $smsSetting->message_postfix);
        
        $data = [
            'to' => $phoneNumber,
            'pre' => $pre, 
            'post' => $post, 
            'from' => $smsSetting->from ?? env('AFROMESSAGE_FROM', ''),
            'sender' => userCompany()->name ?? env('AFROMESSAGE_SENDER', ''),
            'sb' => $smsSetting->space_before ?? env('SPACES_BEFORE', ''),
            'sa' => $smsSetting->space_after ?? env('SPACES_AFTER', ''),
            'ttl' => $smsSetting->time_to_live ?? env('TIME_TO_LIVE', ''),
            'len' => $smsSetting->code_length ?? env('CODE_LENGTH', ''),
            't' => $smsSetting->code_type ?? env('CODE_TYPE', ''),
            'callback' => $smsSetting->callback ?? env('AFROMESSAGE_CALLBACK', '')
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $smsSetting->token ?? env('AFROMESSAGE_TOKEN'),
            'Content-Type' => 'application/json',
        ])->get($smsSetting->security_message_url ?? env('AFROMESSAGE_SECURITY_MESSAGE_URL'), $data);

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