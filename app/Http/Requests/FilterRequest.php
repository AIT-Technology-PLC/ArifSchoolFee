<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            request('branch') => ['nullable', 'integer', Rule::in(authUser()->getAllowedWarehouses('Sales Report')->pluck('id'))],
            request('period') => ['nullable', 'date'],
        ];
    }
}
