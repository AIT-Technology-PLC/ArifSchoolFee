<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use App\Traits\PrependCompanyId;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDamageRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:damages,code,' . $this->route('damage')->id,
            'damage' => 'required|array',
            'damage.*.product_id' => 'required|integer',
            'damage.*.warehouse_id' => 'required|integer',
            'damage.*.quantity' => 'required|numeric|min:1',
            'damage.*.description' => 'nullable|string',
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
