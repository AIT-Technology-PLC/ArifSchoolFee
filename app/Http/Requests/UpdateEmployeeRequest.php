<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'position' => ['required', 'string'],
            'enabled' => ['sometimes', 'required', 'integer', 'max:1'],
            'role' => ['sometimes', 'required', 'string'],
            'warehouse_id' => ['required', 'integer'],
            'read' => ['nullable', 'array'],
            'subtract' => ['nullable', 'array'],
            'add' => ['nullable', 'array'],
            'sales' => ['nullable', 'array'],
            'adjustment' => ['nullable', 'array'],
            'siv' => ['nullable', 'array'],
            'read.*' => ['nullable', 'integer'],
            'subtract.*' => ['nullable', 'integer'],
            'add.*' => ['nullable', 'integer'],
            'sales.*' => ['nullable', 'integer'],
            'adjustment.*' => ['nullable', 'integer'],
            'siv.*' => ['nullable', 'integer'],
        ];
    }
}
