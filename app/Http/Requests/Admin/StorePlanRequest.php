<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:25', Rule::unique('plans')],
            'feature_id' => ['required', 'array'],
            'feature_id.*' => ['required', 'integer'],
        ];
    }
}
