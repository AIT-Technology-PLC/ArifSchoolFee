<?php

namespace App\Http\Requests;

use App\Models\MerchandiseBatch;
use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReturnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('returns'), new CanEditReferenceNumber('returns')],
            'return' => ['required', 'array'],
            'return.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'return.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('add')->pluck('id'))],
            'return.*.unit_price' => ['nullable', 'numeric'],
            'return.*.quantity' => ['required', 'numeric', 'gt:0', function ($attribute, $value, $fail) {
                if (MerchandiseBatch::where('id', $this->input(str_replace('.quantity', '.merchandise_batch_id', $attribute)))->where('quantity', '<', $value)->exists()) {
                    $fail('There is no sufficient amount in this batch, please check your inventory.');
                }
            }],
            'return.*.description' => ['nullable', 'string'],
            'return.*.merchandise_batch_id' => [' nullable', 'integer', new MustBelongToCompany('merchandise_batches'), function ($attribute, $value, $fail) {
                $merchandiseBatch = MerchandiseBatch::firstwhere('id', $value);
                if ($merchandiseBatch->merchandise->product_id != $this->input(str_replace('.merchandise_batch_id', '.product_id', $attribute))) {
                    $fail('Invalid Batch Number!');
                }
            }],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
