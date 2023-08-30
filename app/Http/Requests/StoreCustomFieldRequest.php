<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomFieldRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customField.*.label' => ['required', 'string', 'max:255'],
            'customField.*.placeholder' => ['nullable', 'string', 'max:255'],
            'customField.*.options' => ['nullable', 'string', 'max:255'],
            'customField.*.default_value' => ['nullable', 'string', 'max:255'],
            'customField.*.model_type' => ['required', 'string', 'max:255'],
            'customField.*.order' => ['required', 'integer'],
            'customField.*.column_size' => ['nullable', 'string', 'max:255'],
            'customField.*.icon' => ['nullable', 'string', 'max:255'],
            'customField.*.is_active' => ['required', 'boolean'],
            'customField.*.is_required' => ['required', 'boolean'],
            'customField.*.is_unique' => ['required', 'boolean'],
            'customField.*.is_visible' => ['required', 'boolean'],
            'customField.*.is_printable' => ['required', 'boolean'],
        ];
    }
}
