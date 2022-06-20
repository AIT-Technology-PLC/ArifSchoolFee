<?php

namespace App\Http\Requests;

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
            'code' => ['required', 'integer', new UniqueReferenceNum('jobs', $this->route('job')->id)],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers'), 'prohibited_if:is_internal_job,1'],
            'factory_id' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'assigned_to' => ['nullable', 'integer', new MustBelongToCompany('users')],
            'description' => ['nullable', 'string'],
            'is_internal_job' => ['required', 'boolean'],
            'job' => ['required', 'array'],
            'job.*.product_id' => ['required', 'integer', 'different:product_id', 'distinct', new MustBelongToCompany('products')],
            'job.*.bill_of_material_id' => ['required', 'integer', 'different:bill_of_material_id', 'distinct', new MustBelongToCompany('bill_of_materials')],
            'job.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
