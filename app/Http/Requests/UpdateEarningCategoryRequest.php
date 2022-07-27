<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEarningCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('earning_categories')->where(function ($query) {
                return $query->where('company_id', userCompany()->id)->where('id', '<>', $this->route('earning_category')->id);
            })],
            'type' => ['required', 'string', Rule::in(['Before Tax', 'After Tax'])],
        ];
    }
}
