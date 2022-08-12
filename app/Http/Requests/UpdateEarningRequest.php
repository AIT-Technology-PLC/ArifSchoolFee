<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEarningRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('earnings', $this->route('earning')->id)],
            'starting_period' => ['required', 'date'],
            'ending_period' => ['required', 'date', 'after:starting_period'],
            'description' => ['nullable', 'string'],
            'earning' => ['required', 'array'],
            'earning.*.employee_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('employees'), function ($attribute, $value, $fail) {
                if (!authUser()->getAllowedWarehouses('hr')->where('id', Employee::firstWhere('id', $value)->user->warehouse_id)->count()) {
                    $fail('You do not have permission to modify an earning request of this employee.');
                }
            }],
            'earning.*.employeeEarnings.*.earning_category_id' => ['required', 'integer', new MustBelongToCompany('earning_categories')],
            'earning.*.employeeEarnings.*.amount' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
