<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use App\Traits\PrependCompanyId;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSivRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:sivs,code,' . $this->route('siv')->id,
            'siv' => 'required|array',
            'siv.*.product_id' => 'required|integer',
            'siv.*.warehouse_id' => 'required|integer',
            'siv.*.quantity' => 'required|numeric|min:1',
            'siv.*.description' => 'nullable|string',
            'issued_on' => 'required|date',
            'received_by' => 'nullable|string',
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
