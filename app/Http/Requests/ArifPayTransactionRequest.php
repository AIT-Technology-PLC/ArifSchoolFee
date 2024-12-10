<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ArifPayTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uuid' => 'required|uuid',
            'nonce' => 'required|string',
            'phone' => 'required|string',
            'paymentMethod' => 'required|string',
            'totalAmount' => 'required|numeric',
            'transactionStatus' => 'required|in:SUCCESS,FAILED,CANCELLED,PENDING,UNAUTHORIZED',
            'transaction.transactionId' => 'required|string',
            'transaction.transactionStatus' => 'required|in:SUCCESS,FAILED,CANCELLED,PENDING,UNAUTHORIZED',
            'notificationUrl' => 'required|url',
            'sessionId' => 'required|string',
        ];
    }
}
