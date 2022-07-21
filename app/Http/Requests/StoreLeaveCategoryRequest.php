<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeaveCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'leaveCategory' => ['required', 'array'],
            'leaveCategory.*.name' => ['required', 'string', 'distinct'],
            'leaveCategory.*.type' => ['required', 'string', Rule::in(['paid', 'unpaid'])],
        ];
    }
}