<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreItemTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
            return [
                'item' => ['required', 'array'],
                'item.*.name' => ['required', 'string', 'distinct', Rule::unique('items')->where('company_id', userCompany()->id)->withoutTrashed()],
                'item.*.description' => ['nullable', 'string'],
            ];
    }
}
