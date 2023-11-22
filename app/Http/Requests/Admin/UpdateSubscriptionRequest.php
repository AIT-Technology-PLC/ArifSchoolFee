<?php

namespace App\Http\Requests\Admin;

use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'months' => ['required', 'integer', 'gte:1'],
            'plan_id' => ['required', 'integer', Rule::in(Plan::pluck('id'))],
        ];
    }
}
