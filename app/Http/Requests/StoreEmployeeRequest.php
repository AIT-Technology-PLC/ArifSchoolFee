<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'role' => ['required', 'string'],
            'warehouse_id' => ['required', 'integer'],
            'read' => ['nullable', 'array'],
            'subtract' => ['nullable', 'array'],
            'add' => ['nullable', 'array'],
            'sales' => ['nullable', 'array'],
            'transfer_from' => ['nullable', 'array'],
            'transfer_to' => ['nullable', 'array'],
            'adjustment' => ['nullable', 'array'],
            'siv' => ['nullable', 'array'],
            'read.*' => ['nullable', 'integer'],
            'subtract.*' => ['nullable', 'integer'],
            'add.*' => ['nullable', 'integer'],
            'sales.*' => ['nullable', 'integer'],
            'transfer_from.*' => ['nullable', 'integer'],
            'transfer_to.*' => ['nullable', 'integer'],
            'adjustment.*' => ['nullable', 'integer'],
            'siv.*' => ['nullable', 'integer'],
        ];
    }
}
