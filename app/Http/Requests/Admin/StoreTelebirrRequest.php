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
            'TELEBIRR_CHECKOUT_URL' => ['required', 'string','max:100'],
            'TELEBIRR_API_KEY' => ['required', 'string'],
            'TELEBIRR_SECRET_KEY' => ['required', 'string'],
            'TELEBIRR_CLIENT_ID' => ['required', 'string'],
        ];
    }
}
