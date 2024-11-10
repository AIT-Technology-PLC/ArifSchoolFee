<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDesignationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'designation' => ['required', 'array'],
            'designation.*.name' => ['required', 'string', 'distinct', 'max:30', Rule::unique('designations', 'name')->where('company_id', userCompany()->id)->withoutTrashed()],
        ];
    }
}