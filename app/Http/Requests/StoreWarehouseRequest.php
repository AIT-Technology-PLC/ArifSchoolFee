<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'pos_provider' => ['nullable', 'string', 'max:255', Rule::in(['Peds'])],
            'host_address' => ['nullable', 'required_with:pos_provider', 'string'],
            'is_active' => ['required', 'boolean'],
            'is_sales_store' => ['required', 'boolean'],
            'can_be_sold_from' => ['required', 'boolean'],
            'email' => ['nullable', 'string', 'email'],
            'phone' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }
}
