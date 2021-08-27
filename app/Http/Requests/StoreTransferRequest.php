<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use App\Traits\PrependCompanyId;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:transfers',
            'transfer' => 'required|array',
            'transfer.*.product_id' => 'required|integer',
            'transfer.*.warehouse_id' => 'required|integer',
            'transfer.*.to_warehouse_id' => 'required|integer|different:transfer.*.warehouse_id',
            'transfer.*.quantity' => 'required|numeric|min:1',
            'transfer.*.description' => 'nullable|string',
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
        $this->merge([
            'status' => 'Not Transferred',
        ]);

        $this->merge(SetDataOwnerService::forTransaction());
    }
}
