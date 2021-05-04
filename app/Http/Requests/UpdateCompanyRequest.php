<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sector' => 'nullable|string|max:255',
            'currency' => 'required|string|max:255',
            'email' => 'nullable|string|email|',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'logo' => 'sometimes|file',
        ];
    }
}
