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
            'AFROMESSAGE_SINGLE_MESSAGE_URL' => ['required', 'string','max:100'],
            'AFROMESSAGE_BULK_MESSAGE_URL' => ['required', 'string','max:100'],
            'AFROMESSAGE_SECURITY_MESSAGE_URL' => ['required', 'string','max:100'],
            'AFROMESSAGE_FROM' => ['required', 'string','max:100'],
            'AFROMESSAGE_TOKEN' => ['required', 'string'],
            'AFROMESSAGE_SENDER' => ['nullable', 'string', 'max:20'],
            'AFROMESSAGE_CALLBACK' => ['nullable', 'string','max:50'],
            'AFROMESSAGE_CAMPAIGN_NAME' => ['nullable', 'string'],
            'AFROMESSAGE_CREATE_CALLBACK' => ['nullable', 'string', 'max:50'],
            'AFROMESSAGE_STATUS_CALLBACK' => ['nullable', 'string', 'max:50'],
            'SPACES_BEFORE' => ['nullable', 'numeric', 'between:0,3'],
            'SPACES_AFTER' => ['nullable', 'numeric', 'between:0,3'],
            'TIME_TO_LIVE' => ['nullable', 'numeric', 'gte:0'],
            'CODE_LENGTH' => ['nullable', 'numeric', 'between:3,6'],
            'CODE_TYPE' => ['nullable', 'numeric', 'between:0,2'],
        ];
    }
}
