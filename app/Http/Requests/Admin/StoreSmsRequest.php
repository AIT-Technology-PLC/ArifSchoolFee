<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSmsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'single_message_url' => ['required', 'string'],
            'bulk_message_url' => ['required', 'string'],
            'security_message_url' => ['required', 'string'],
            'from' => ['required', 'string','max:100'],
            'token' => ['required', 'integer'],
            'sender' => ['nullable', 'string', 'max:20'],
            'callback' => ['nullable', 'string','max:50'],
            'compaign' => ['nullable', 'string'],
            'create_callback' => ['nullable', 'string', 'max:50'],
            'status_callback' => ['nullable', 'string', 'max:50'],
            'message_prefix' => ['nullable', 'string', 'max:3'],
            'message_postfix' => ['nullable', 'string', 'max:3'],
            'space_before' => ['nullable', 'numeric', 'between:0,3'],
            'space_after' => ['nullable', 'numeric', 'between:0,3'],
            'time_to_live' => ['nullable', 'numeric', 'gte:0'],
            'code_length' => ['nullable', 'numeric', 'between:3,6'],
            'code_type' => ['nullable', 'numeric', 'between:0,2'],
        ];
    }
}
