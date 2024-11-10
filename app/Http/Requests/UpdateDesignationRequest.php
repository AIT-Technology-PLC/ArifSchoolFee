<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDesignationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:30', Rule::unique('designations')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('designation')->id)->withoutTrashed()],
        ];
    }
}
