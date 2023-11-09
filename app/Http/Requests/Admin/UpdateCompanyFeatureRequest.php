<?php

namespace App\Http\Requests\Admin;

use App\Models\Feature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyFeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enable.*' => ['nullable', 'integer', Rule::in(Feature::pluck('id'))],
            'disable.*' => ['nullable', 'integer', 'different:enable.*', Rule::in(Feature::pluck('id'))],
        ];
    }
}
