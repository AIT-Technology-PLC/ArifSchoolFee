<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeeDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('fee_discounts')->where('company_id', userCompany()->id)->withoutTrashed()],
            'discount_code' => ['nullable', 'string', 'max:10'],
            'discount_type'=> ['required', 'string', 'max:15'],
            'amount' => ['required','numeric','gt:0'],
            'description' => ['nullable', 'string', 'max:50'],
        ];
    }
}
