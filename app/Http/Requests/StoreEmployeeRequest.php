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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'position' => ['required', 'string'],
            'enabled' => ['required', 'integer', 'max:1'],
            'role' => ['required', 'string', Rule::notIn(['System Manager'])],
            'warehouse_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'read' => ['nullable', 'array'],
            'subtract' => ['nullable', 'array'],
            'add' => ['nullable', 'array'],
            'sales' => ['nullable', 'array'],
            'adjustment' => ['nullable', 'array'],
            'siv' => ['nullable', 'array'],
            'read.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'subtract.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'add.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'sales.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'adjustment.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
            'siv.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
        ];
    }
}
