<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'customField.*.tag' => ['required', 'string', 'max:255', Rule::in(['input', 'select', 'textarea'])],
            'customField.*.tag_type' => ['nullable', 'string', 'max:255', 'required_if:customField.*.tag,input', 'prohibited_unless:customField.*.tag,input', Rule::in(['text', 'number', 'date', 'radio'])],
            'customField.*.placeholder' => ['nullable', 'string', 'max:255'],
            'customField.*.options' => ['nullable', 'string', 'max:255', 'required_if:customField.*.tag,select', 'required_if:customField.*.tag_type,radio'],
            'customField.*.default_value' => ['nullable', 'string', 'max:255'],
            'customField.*.model_type' => ['required', 'string', 'max:255'],
            'customField.*.order' => ['required', 'integer'],
            'customField.*.column_size' => ['required', 'string', 'max:255'],
            'customField.*.icon' => ['required', 'string', 'max:255'],
            'customField.*.is_active' => ['required', 'boolean'],
            'customField.*.is_required' => ['required', 'boolean'],
            'customField.*.is_unique' => ['required', 'boolean'],
            'customField.*.is_visible' => ['required', 'boolean'],
            'customField.*.is_printable' => ['required', 'boolean'],
            'customField.*.is_master' => ['required', 'boolean'],
        ];
    }
}
