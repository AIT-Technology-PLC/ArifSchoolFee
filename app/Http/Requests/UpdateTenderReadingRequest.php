<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenderReadingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'financial_reading' => ['nullable', 'string'],
            'technical_reading' => ['nullable', 'string'],
        ];
    }
}
