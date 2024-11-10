<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MustBelongToCompany;

class StoreRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'route' => ['required', 'array'],
            'route.*.title' => ['required', 'string', 'distinct', Rule::unique('routes')->where('company_id', userCompany()->id)->withoutTrashed()],
            'route.*.fare' => ['required', 'numeric', 'gte:0'],
            'route.*.vehicle_id' => ['required', 'array'],
            'route.*.vehicle_id.*' => ['required', 'integer', 'distinct', new MustBelongToCompany('vehicles')],
        ];
    }
}
