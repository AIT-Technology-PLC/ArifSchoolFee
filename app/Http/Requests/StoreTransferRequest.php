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
            'transfer.*.quantity' => 'required|numeric|min:1',
            'transfer.*.description' => 'nullable|string',
            'transferred_from' => 'required|integer',
            'transferred_to' => 'required|integer|different:transferred_from',
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
        $this->merge(SetDataOwnerService::forTransaction());
    }
}
