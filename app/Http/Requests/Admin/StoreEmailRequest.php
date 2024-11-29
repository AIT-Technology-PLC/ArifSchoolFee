<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'MAIL_FROM_NAME' => ['required', 'string', 'max:50'],
            'MAIL_FROM_ADDRESS' => ['required', 'string', 'email', 'max:50'],
            'MAIL_MAILER' => ['required', 'string', 'max:50'],
            'MAIL_HOST' => ['required', 'string', 'max:50'],
            'MAIL_PORT' => ['required', 'integer'],
            'MAIL_USERNAME' => ['required', 'string', 'email', 'max:50'],
            'MAIL_PASSWORD' => ['required', 'string', 'max:50'],
            'MAIL_ENCRYPTION' => ['required', 'string', 'max:50'],
        ];
    }
}
