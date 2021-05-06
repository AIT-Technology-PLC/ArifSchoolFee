<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use App\Traits\PrependCompanyId;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGrnRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:grns,code,' . $this->route('grn')->id,
            'grn' => 'required|array',
            'grn.*.product_id' => 'required|integer',
            'grn.*.warehouse_id' => 'required|integer',
            'grn.*.quantity' => 'required|numeric|min:1',
            'grn.*.description' => 'nullable|string',
            'supplier_id' => 'nullable|integer',
            'purchase_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'description' => 'nullable|string',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'code' => $this->prependCompanyId($this->code),
        ]);
    }

    public function passedValidation()
    {
        $this->merge(SetDataOwnerService::forUpdate());
    }
}
