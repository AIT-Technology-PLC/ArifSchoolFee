<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFeeGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('fee_groups')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('fee_group')->id)->withoutTrashed()],
            'description' => ['nullable', 'string', 'max:50'],
        ];
    }
}
