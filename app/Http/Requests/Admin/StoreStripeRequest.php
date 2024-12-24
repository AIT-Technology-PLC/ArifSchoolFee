<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStripeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'STRIPE_SECRET_KEY' => ['required', 'string'],
            'STRIPE_PUBLISHABLE_KEY' => ['required', 'string'],
            'STRIPE_PAYMENT_MODE' => ['required', 'string','max:50'],
        ];
    }
}
