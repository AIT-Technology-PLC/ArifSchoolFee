<?php

namespace App\Http\Requests;

use App\Models\Pad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'abbreviation' => ['required', 'string'],
            'icon' => ['required', 'string'],
            'inventory_operation_type' => ['required', 'string', Rule::in(Pad::INVENTORY_OPERATIONS)],
            'is_approvable' => ['required', 'boolean'],
            'is_printable' => ['required', 'boolean'],
            'has_prices' => ['required', 'boolean'],
            'has_payment_term' => ['required', 'boolean'],
            'is_enabled' => ['required', 'boolean'],
            'module' => ['required', 'string', Rule::in(Pad::MODULES)],
            'convert_to' => ['nullable', 'array', Rule::in((new Pad)->converts())],
            'convert_from' => ['nullable', 'array', Rule::in((new Pad)->converts())],
            'print_orientation' => ['required', 'string'],
            'print_paper_size' => ['required', 'string'],

            'field' => ['nullable', 'array'],
            'status' => ['nullable', 'array'],

            'status.*.name' => ['sometimes', 'required', 'string', 'distinct', 'required_with:status'],
            'status.*.bg_color' => ['nullable', 'string', 'required_with:status'],
            'status.*.text_color' => ['nullable', 'string', 'required_with:status'],
            'status.*.is_active' => ['nullable', 'boolean', 'required_with:status'],
            'status.*.is_editable' => ['nullable', 'boolean', 'required_with:status'],
            'status.*.is_deletable' => ['nullable', 'boolean', 'required_with:status'],

            'field.*.is_relational_field' => ['sometimes', 'required', 'boolean', 'required_with:field'],
            'field.*.list' => ['nullable', 'string', 'required_if:field.*.is_relational_field,1', 'exclude_if:field.*.is_relational_field,0', Rule::in(Pad::COMPONENTS)],

            'field.*.label' => ['nullable', 'string', 'required_if:field.*.is_relational_field,0', 'exclude_if:field.*.is_relational_field,1'],
            'field.*.icon' => ['nullable', 'string', 'required_if:field.*.is_relational_field,0', 'exclude_if:field.*.is_relational_field,1'],
            'field.*.tag' => ['nullable', 'string', 'required_if:field.*.is_relational_field,0', 'exclude_if:field.*.is_relational_field,1'],
            'field.*.tag_type' => ['nullable', 'string', 'required_if:field.*.tag,input', 'exclude_unless:field.*.tag,input,select'],
            'field.*.is_master_field' => ['sometimes', 'required', 'boolean', 'required_with:field'],
            'field.*.is_required' => ['sometimes', 'required', 'boolean', 'required_with:field'],
            'field.*.is_visible' => ['sometimes', 'required', 'boolean', 'required_with:field'],
            'field.*.is_printable' => ['sometimes', 'required', 'boolean', 'required_with:field'],
            'field.*.is_readonly' => ['sometimes', 'required', 'boolean', 'required_with:field'],
        ];
    }
}
