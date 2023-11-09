<?php

namespace App\Http\Requests\Admin;

use App\Models\Integration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyIntegrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'integrations.*' => ['nullable', 'integer', Rule::in(Integration::enabled()->pluck('id'))],
        ];
    }
}
