<?php

namespace App\Http\Requests;

use App\Models\BillOfMaterial;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('job_orders', $this->route('job')->id)],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers'), 'prohibited_if:is_internal_job,1'],
            'factory_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'description' => ['nullable', 'string'],
            'issued_on' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issued_on'],
            'is_internal_job' => ['required', 'boolean'],
            'job' => ['required', 'array'],
            'job.*.product_id' => ['required', 'integer', 'different:product_id', 'distinct', new MustBelongToCompany('products')],
            'job.*.bill_of_material_id' => ['required', 'integer', new MustBelongToCompany('bill_of_materials'), function ($attribute, $value, $fail) {
                if (BillOfMaterial::where('id', $value)->where('product_id', $this->input(str_replace('.bill_of_material_id', '.product_id', $attribute)))->doesntExist()) {
                    $fail('Invalid bill of material!');
                }}],
            'job.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
