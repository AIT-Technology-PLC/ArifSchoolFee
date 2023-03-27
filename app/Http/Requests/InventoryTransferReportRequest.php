<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryTransferReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'from' => ['nullable', 'integer', Rule::in(authUser()->getAllowedWarehouses('transactions')->pluck('id'))],
            'to' => ['nullable', 'integer', Rule::in(authUser()->getAllowedWarehouses('transactions')->pluck('id'))],
            'period' => ['required', 'array'],
            'period.*' => ['required', 'date'],
            'user_id' => ['nullable', 'integer', new MustBelongToCompany('users')],
            'product_id' => ['nullable', 'integer', new MustBelongToCompany('products')],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'period' => is_null($this->input('period')) ? [today(), today()] : dateRangePicker($this->input('period')),
        ]);
    }
}