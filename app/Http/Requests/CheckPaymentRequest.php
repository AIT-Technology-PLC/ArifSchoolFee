<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MustBelongToCompany;

class CheckPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gte:0'],
            'fine_amount' => ['nullable', 'numeric', 'gte:0'],
            'fee_discount_id' => ['nullable', 'integer', new MustBelongToCompany('fee_discounts')],
            'discount_amount' => ['nullable', 'numeric', 'gte:0', 'required_unless:fee_discount_id,null'],
            'payment_mode' => ['required', 'string'],
        ];
    }
}
