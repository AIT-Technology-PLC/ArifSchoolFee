<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MustBelongToCompany;

class UpdateRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:15', Rule::unique('routes')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('route')->id)->withoutTrashed()],
            'fare' => ['required', 'numeric', 'gte:0'],
            'vehicle_id' => ['required', 'array'],
            'vehicle_id.*' => ['required', 'integer', new MustBelongToCompany('vehicles')],
        ];
    }
}
