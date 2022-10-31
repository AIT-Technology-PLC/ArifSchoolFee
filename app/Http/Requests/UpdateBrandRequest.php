<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'distinct', Rule::unique('brands')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('brand')->id)->withoutTrashed()],
            'description' => ['nullable', 'string'],
        ];
    }
}
