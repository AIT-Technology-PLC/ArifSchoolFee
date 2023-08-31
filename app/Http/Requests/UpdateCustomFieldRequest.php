<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomFieldRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'options' => ['nullable', 'string', 'max:255'],
            'default_value' => ['nullable', 'string', 'max:255'],
            'model_type' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer'],
            'column_size' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'is_required' => ['required', 'boolean'],
            'is_unique' => ['required', 'boolean'],
            'is_visible' => ['required', 'boolean'],
            'is_printable' => ['required', 'boolean'],
        ];
    }
}
