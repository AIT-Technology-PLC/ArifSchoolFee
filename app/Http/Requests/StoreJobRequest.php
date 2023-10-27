<?php

namespace App\Http\Requests;

use App\Models\BillOfMaterial;
use App\Models\Product;
use App\Rules\CanEditReferenceNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateCustomFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('job_orders'), new CanEditReferenceNumber('job_orders')],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers'), 'prohibited_if:is_internal_job,1'],
            'factory_id' => ['required', 'integer', Rule::in(auth()->user()->getAllowedWarehouses('sales')->pluck('id'))],
            'description' => ['nullable', 'string'],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'due_date' => ['required', 'date', 'after_or_equal:issued_on'],
            'is_internal_job' => ['required', 'boolean'],
            'job' => ['required', 'array'],
            'job.*.product_id' => ['required', 'integer', 'distinct', Rule::in(Product::activeForJob()->finishedGoods()->pluck('id'))],
            'job.*.bill_of_material_id' => ['required', 'integer', new MustBelongToCompany('bill_of_materials'), function ($attribute, $value, $fail) {
                if (BillOfMaterial::where('id', $value)->where('product_id', $this->input(str_replace('.bill_of_material_id', '.product_id', $attribute)))->doesntExist()) {
                    $fail('Invalid bill of material!');
                }
            }],
            'job.*.quantity' => ['required', 'numeric', 'gt:0'],
            'customField.*' => [new ValidateCustomFields('job')],
        ];
    }
}
