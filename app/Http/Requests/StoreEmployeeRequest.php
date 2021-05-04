<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|string',
            'enabled' => 'required|integer|max:1',
            'role' => 'required|string',
        ];
    }

    public function passedValidation()
    {
        $this->merge(SetDataOwnerService::forNonTransaction());
    }
}
