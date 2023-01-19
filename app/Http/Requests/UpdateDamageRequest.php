<?php

namespace App\Http\Requests;

use App\Models\MerchandiseBatch;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDamageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('damages', $this->route('damage')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'damage' => ['required', 'array'],
            'damage.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'damage.*.warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('subtract')->pluck('id'))],
            'damage.*.quantity' => ['required', 'numeric', 'gt:0', function ($attribute, $value, $fail) {
                if (MerchandiseBatch::where('id', $this->input(str_replace('.quantity', '.merchandise_batch_id', $attribute)))->where('quantity', '<', $value)->exists()) {
                    $fail('There is no sufficient amount in this batch, please check your inventory.');
                }
            }],
            'damage.*.description' => ['nullable', 'string'],
            'damage.*.merchandise_batch_id' => [' nullable', 'integer', new MustBelongToCompany('merchandise_batches'), function ($attribute, $value, $fail) {
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
