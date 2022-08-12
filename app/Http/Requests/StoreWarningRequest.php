<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWarningRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'warning' => ['required', 'array'],
            'warning.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees'), function ($attribute, $value, $fail) {
                if (!authUser()->getAllowedWarehouses('hr')->where('id', Employee::firstWhere('id', $value)->user->warehouse_id)->count()) {
                    $fail('You do not have permission to create a warning request for this employee.');
                }
            }],
            'warning.*.type' => ['required', 'string', 'max:255', Rule::in(['Initial Warning', 'Affirmation Warning', 'Final Warning'])],
            'warning.*.issued_on' => ['required', 'date'],
            'warning.*.letter' => ['required', 'string'],
        ];
    }
}
