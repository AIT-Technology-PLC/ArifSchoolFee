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
            'purpose' => 'nullable|string',
            'ref_num' => 'nullable|required_unless:purpose,null|prohibited_if:purpose,null|string',
            'siv' => 'required|array',
            'siv.*.product_id' => 'required|integer',
            'siv.*.warehouse_id' => 'required|integer',
            'siv.*.quantity' => 'required|numeric|min:1',
            'siv.*.description' => 'nullable|string',
            'issued_on' => 'required|date',
            'received_by' => 'nullable|string',
            'delivered_by' => 'nullable|string',
            'issued_to' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'ref_num.required_unless' => 'The Ref No is required for the purpose selected',
            'ref_num.prohibited_if' => 'The Ref No field requires one of the purposes to be selected',
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
