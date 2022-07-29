<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeaveCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'distinct', Rule::unique('leave_categories')->where(function ($query) {
                return $query->where('company_id', userCompany()->id)->where('id', '<>', $this->route('leave_category')->id);
            })],
        ];
    }
}