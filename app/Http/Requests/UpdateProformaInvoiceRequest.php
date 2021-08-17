<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use App\Traits\PrependCompanyId;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProformaInvoiceRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:proforma_invoices,code,' . $this->route('proforma_invoice')->id,
            'customer_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'expires_on' => 'nullable|date|after_or_equal:issued_on',
            'terms' => 'nullable|string',
            'proformaInvoice' => 'required|array',
            'proformaInvoice.*.product_id' => 'required|integer',
            'proformaInvoice.*.quantity' => 'required|numeric|min:1',
            'proformaInvoice.*.unit_price' => 'required|numeric',
            'proformaInvoice.*.discount' => 'nullable|numeric|min:0|max:100',
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
