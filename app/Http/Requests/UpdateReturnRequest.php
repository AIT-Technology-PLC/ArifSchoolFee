<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use App\Traits\PrependCompanyId;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReturnRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', 'unique:returns,code,' . $this->route('return')->id],
            'return' => ['required', 'array'],
            'return.*.product_id' => ['required', 'integer'],
            'return.*.warehouse_id' => ['required', 'integer'],
            'return.*.unit_price' => ['nullable', 'numeric'],
            'return.*.quantity' => ['required', 'numeric', 'min:1'],
            'return.*.description' => ['nullable', 'string'],
            'customer_id' => ['nullable', 'integer'],
            'issued_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
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
