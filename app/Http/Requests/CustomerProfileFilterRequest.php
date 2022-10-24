<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerProfileFilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'branches' => ['required', 'array'],
            'branches.*' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('transactions')->pluck('id'))],
            'period' => ['nullable', 'array'],
            'period.*' => ['nullable', 'date'],
            'customer_id' => ['required', 'integer', new MustBelongToCompany('customers')],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'branches' => is_null($this->input('branches')) ? authUser()->getAllowedWarehouses('transactions')->pluck('id')->toArray() : [$this->input('branches')],
            'period' => is_null($this->input('period')) ? null : dateRangePicker($this->input('period')),
            'customer_id' => $this->route('customer')->id,
        ]);
    }
}
