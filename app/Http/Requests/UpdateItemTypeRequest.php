<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'distinct', Rule::unique('items')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('item')->id)->withoutTrashed()],
            'description' => ['nullable', 'string'],
        ];
    }
}
