<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('transfers', $this->route('transfer')->id)],
            'transfer' => ['required', 'array'],
            'transfer.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'transfer.*.quantity' => ['required', 'numeric', 'min:1'],
            'transfer.*.description' => ['nullable', 'string'],
            'transferred_from' => ['required', 'integer', Rule::in(auth()->user()->getAllowedWarehouses('subtract')->pluck('id'))],
            'transferred_to' => ['required', 'integer', 'different:transferred_from', Rule::in(auth()->user()->getAllowedWarehouses('add')->pluck('id'))],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
