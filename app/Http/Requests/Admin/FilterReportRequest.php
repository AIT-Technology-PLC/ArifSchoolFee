<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FilterReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period' => ['required', 'array'],
            'period.*' => ['required', 'date'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'period' => is_null($this->input('period')) ? [today(), today()->addMonths(3)] : dateRangePicker($this->input('period')),
        ]);
    }
}
