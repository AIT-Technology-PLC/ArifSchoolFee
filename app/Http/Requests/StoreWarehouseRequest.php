<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use Illuminate\Foundation\Http\FormRequest;

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
            'is_sales_store' => ['required', 'boolean'],
            'can_be_sold_from' => ['required', 'boolean'],
            'email' => ['nullable', 'string', 'email'],
            'phone' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function passedValidation()
    {
        $this->merge(SetDataOwnerService::forNonTransaction());
    }
}
