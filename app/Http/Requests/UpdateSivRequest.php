<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSivRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('sivs', $this->route('siv')->id)],
            'purpose' => ['nullable', 'string'],
            'ref_num' => ['nullable', 'required_unless:purpose,null', 'prohibited_if:purpose,null', 'string'],
            'siv' => ['required', 'array'],
            'siv.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'siv.*.warehouse_id' => ['required', 'integer', Rule::in(auth()->user()->getAllowedWarehouses('siv')->pluck('id'))],
            'siv.*.quantity' => ['required', 'numeric', 'min:1'],
            'siv.*.description' => ['nullable', 'string'],
            'issued_on' => ['required', 'date'],
            'received_by' => ['nullable', 'string'],
            'delivered_by' => ['nullable', 'string'],
            'issued_to' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'ref_num.required_unless' => 'The Ref No is required for the purpose selected',
            'ref_num.prohibited_if' => 'The Ref No field requires one of the purposes to be selected',
        ];
    }
}
