<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobExtraRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'job' => ['required', 'array'],
            'job.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'job.*.quantity' => ['required', 'numeric', 'gt:0'],
            'job.*.type' => ['required', 'string', 'max:255', Rule::in(['Input', 'Remaining'])],
        ];
    }
}
