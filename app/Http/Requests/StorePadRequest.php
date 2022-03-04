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
            'abbreviation' => ['nullable', 'string'],
            'inventory_operation_type' => ['required', 'string', Rule::in(Pad::INVENTORY_OPERATIONS)],
            'is_approvable' => ['required', 'boolean'],
            'is_closable' => ['required', 'boolean'],
            'is_cancellable' => ['required', 'boolean'],
            'is_printable' => ['required', 'boolean'],
            'has_prices' => ['required', 'boolean'],
            'is_enabled' => ['required', 'boolean'],
            'module' => ['required', 'string', Rule::in(Pad::MODULES)],

            'fields.*.pad_relation_id' => ['nullable', 'integer'],
            'fields.*.relationship_type' => ['nullable', 'string', 'required_unless:fields.*.pad_relation_id,null', 'prohibited_if:fields.*.pad_relation_id,null'],
            'fields.*.model_name' => ['nullable', 'string', 'required_unless:fields.*.pad_relation_id,null', 'prohibited_if:fields.*.pad_relation_id,null'],
            'fields.*.primary_key' => ['nullable', 'string', 'required_unless:fields.*.pad_relation_id,null', 'prohibited_if:fields.*.pad_relation_id,null'],

            'fields.*.label' => ['required', 'string'],
            'fields.*.icon' => ['required', 'string'],
            'fields.*.is_master_field' => ['required', 'boolean'],
            'fields.*.is_required' => ['required', 'boolean'],
            'fields.*.is_visible' => ['required', 'boolean'],
            'fields.*.is_printable' => ['required', 'boolean'],
            'fields.*.tag' => ['required', 'string'],
            'fields.*.tag_type' => ['nullable', 'string', 'required_if:fields.*.tag,input', 'prohibited_unless:fields.*.tag,input'],

            'permissions.*.name' => ['required', 'string'],
        ];
    }
}
