<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:10', 'distinct', Rule::unique('sections')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('section')->id)->withoutTrashed()],
        ];
    }
}
