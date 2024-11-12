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
            'from_name' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z\s]+$/'],
            'from_mail' => ['required', 'string', 'email', 'max:40'],
            'mail_driver' => ['required', 'string', 'max:6'],
            'mail_host' => ['required', 'string', 'max:20'],
            'mail_port' => ['required', 'integer'],
            'mail_username' => ['required', 'string', 'email', 'max:40'],
            'mail_password' => ['required', 'string', 'max:25'],
            'mail_encryption' => ['required', 'string', 'max:6'],
        ];
    }

    public function messages()
    {
        return [
            'from_name.regex' => 'Should contain only letters and spaces',
        ];
    }
}
