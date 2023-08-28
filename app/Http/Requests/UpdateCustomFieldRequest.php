<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'tag' => ['required', 'string', 'max:255', Rule::in(['input', 'select', 'textarea'])],
            'tag_type' => ['nullable', 'string', 'max:255', 'required_if:tag,input', 'prohibited_unless:tag,input', Rule::in(['text', 'number', 'date', 'radio'])],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'options' => ['nullable', 'string', 'max:255', 'required_if:tag,select', 'required_if:tag_type,radio'],
            'default_value' => ['nullable', 'string', 'max:255'],
            'model_type' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer'],
            'column_size' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'is_required' => ['required', 'boolean'],
            'is_visible' => ['required', 'boolean'],
            'is_printable' => ['required', 'boolean'],
            'is_master' => ['required', 'boolean'],
        ];
    }
}
