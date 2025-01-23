<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class TelebirrTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appid' => 'required|string',
            'notify_time' => 'required|string',
            'merch_code' => 'required|string',
            'merch_order_id' => 'required|string',
            'payment_order_id' => 'required|string',
            'total_amount' => 'required|numeric',
            'trans_id' => 'required|string',
            'trans_currency' => 'required|string',
            'trade_status' => 'required|in:Completed',
            'trans_end_time' => 'required|string',
            'trans_end_time' => 'required|string',
            
        ];
    }
}