<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'brand' => ['required', 'array'],
            'brand.*.name' => ['required', 'string', 'distinct', Rule::unique('brands')->where('company_id', userCompany()->id)->withoutTrashed()],
            'brand.*.description' => ['nullable', 'string'],
        ];
    }
}
