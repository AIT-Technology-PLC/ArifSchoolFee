<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'branch' => ['nullable', 'integer', Rule::in(authUser()->getAllowedWarehouses('sales')->pluck('id'))],
            'period' => ['nullable', 'array'],
            'period.*' => ['nullable', 'date'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'period' => is_null($this->input('period')) ? null : dateRangePicker($this->input('period')),
        ]);
    }
}
