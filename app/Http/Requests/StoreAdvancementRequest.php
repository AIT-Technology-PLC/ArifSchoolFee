<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdvancementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'issued_on' => ['required', 'date'],
            'type' => ['required', 'string', 'max:255', Rule::in(['Promotion', 'Demotion'])],
            'description' => ['nullable', 'string'],
            'advancement' => ['required', 'array'],
            'advancement.*.employee_id' => ['required', 'integer', new MustBelongToCompany('employees'), function ($attribute, $value, $fail) {
                if (!authUser()->getAllowedWarehouses('hr')->where('id', Employee::firstWhere('id', $value)->user->warehouse_id)->count()) {
                    $fail('You do not have permission to create an advancement request for this employee.');
                }
            }],
            'advancement.*.job_position' => ['required', 'string'],
            'advancement.*.compensation_id' => ['required', 'string', new MustBelongToCompany('compensations')],
            'advancement.*.amount' => ['required', 'numeric'],
        ];
    }
}
