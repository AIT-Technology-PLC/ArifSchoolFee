<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ArifPayTransactionDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transactionStatus' => 'required|in:SUCCESS,FAILED,CANCELLED,PENDING',
            'sessionID' => 'required|string|max:255',           
            'id' => 'nullable|integer',
            'notificationUrl' => 'required|url',
            'uuid' => 'required|uuid',
            'nonce' => 'required|string',
            'phone' => 'required|string',
            'totalAmount' => 'required|numeric',
            'transaction.transactionId' => 'required|string',
            'transaction.transactionStatus' => 'same:transactionStatus',
            'sessionId' => 'same:transaction.transactionId',
        ];
    }
}
