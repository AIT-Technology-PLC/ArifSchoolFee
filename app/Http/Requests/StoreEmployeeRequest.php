<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:30', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'position' => ['required', 'string'],
            'gender' => ['required', 'string', 'max:5', Rule::in(['male', 'female'])],
            'phone' => ['required', 'number', 'max:15'],
            'address' => ['required', 'string', 'max:50'],
            'warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'role' => ['required', 'string', Rule::notIn(['System Manager'])],
            'enabled' => ['required', 'integer', 'max:1'],
            'transactions' => ['nullable', 'array'],
            'transactions.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
        ];
    }
}
