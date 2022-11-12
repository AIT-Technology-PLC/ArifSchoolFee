<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date' => ['nullable', 'date'],
            'period_type' => ['nullable', 'string', Rule::in(['ending', 'beginning'])],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'date' => $this->input('date') ?: today(),
        ]);
    }
}
