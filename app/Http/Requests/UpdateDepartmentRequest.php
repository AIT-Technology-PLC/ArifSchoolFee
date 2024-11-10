<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:30', Rule::unique('departments')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('department')->id)->withoutTrashed()],
        ];
    }
}
