<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenderChecklistTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'is_sensitive' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
