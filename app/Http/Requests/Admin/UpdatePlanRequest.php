<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:25', Rule::unique('plans')->ignore($this->route('plan')->id)],
            'feature_id' => ['required', 'array'],
            'feature_id.*' => ['required', 'integer'],
        ];
    }
}
