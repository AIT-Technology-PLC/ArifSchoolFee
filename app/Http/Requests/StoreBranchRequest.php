<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBranchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:25'],
            'location' => ['required', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
            'email' => ['nullable', 'string', 'email', 'max:30'],
            'phone' => ['nullable', 'string','max:15', Rule::unique('warehouses')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('branch')->id)->withoutTrashed()],
        ];
    }
}
