<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTelebirrRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'TELEBIRR_BASE_URL' => ['required', 'string'],
            'TELEBIRR_MERCHANT_APP_ID' => ['required', 'string'],
            'TELEBIRR_MERCHANT_CODE' => ['required', 'string'],
            'TELEBIRR_FABRIC_APP_ID' => ['required', 'string'],
            'TELEBIRR_APP_SECRET' => ['required', 'string'],
            'TELEBIRR_PRIVATE_KEY' => ['required', 'string'],
        ];
    }
}
