<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePaypalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'AFIFPAY_CHECKOUT_URL' => ['required', 'string','max:100'],
            'ARIFPAY_SECRET_KEY' => ['required', 'string','max:100'],
            'ARIFPAY_API_KEY' => ['required', 'string'],
        ];
    }
}
