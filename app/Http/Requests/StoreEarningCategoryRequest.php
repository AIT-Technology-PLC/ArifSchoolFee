<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEarningCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'earningCategory' => ['required', 'array'],
            'earningCategory.*.name' => ['required', 'string', 'distinct', 'max:255', Rule::unique('earning_categories', 'name')->where(function ($query) {
                return $query->where('company_id', userCompany()->id);
            })],
            'earningCategory.*.type' => ['required', 'string', Rule::in(['Before Tax', 'After Tax'])],
        ];
    }
}
