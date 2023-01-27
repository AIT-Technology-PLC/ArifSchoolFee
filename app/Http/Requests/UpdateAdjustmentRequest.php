<?php

namespace App\Http\Requests;

use App\Models\MerchandiseBatch;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdjustmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('adjustments', $this->route('adjustment')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'adjustment' => ['required', 'array'],
            'adjustment.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('adjustment')->pluck('id'))],
            'adjustment.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'adjustment.*.is_subtract' => ['required', 'integer'],
            'adjustment.*.quantity' => ['required', 'numeric', 'gt:0', function ($attribute, $value, $fail) {
                if (MerchandiseBatch::where('id', $this->input(str_replace('.quantity', '.merchandise_batch_id', $attribute)))->where('quantity', '<', $value)->exists()) {
                    $fail('There is no sufficient amount in this batch, please check your inventory.');
                }
            }],
            'adjustment.*.reason' => ['required', 'string'],
            'adjustment.*.merchandise_batch_id' => [' nullable', 'integer', new MustBelongToCompany('merchandise_batches'), function ($attribute, $value, $fail) {
                $merchandiseBatch = MerchandiseBatch::firstwhere('id', $value);
                if ($merchandiseBatch->merchandise->product_id != $this->input(str_replace('.merchandise_batch_id', '.product_id', $attribute))) {
                    $fail('Invalid Batch Number!');
                }
            }],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
