<?php

namespace App\Http\Requests;

use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

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
            'transfer.*.product_id' => ['required', 'integer'],
            'transfer.*.quantity' => ['required', 'numeric', 'min:1'],
            'transfer.*.description' => ['nullable', 'string'],
            'transferred_from' => ['required', 'integer'],
            'transferred_to' => ['required', 'integer', 'different:transferred_from'],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
