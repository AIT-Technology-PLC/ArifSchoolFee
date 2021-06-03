<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use Illuminate\Foundation\Http\FormRequest;

class StoreProformaInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:proforma_invoices',
            'customer_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'expires_on' => 'nullable|date',
            'terms' => 'nullable|string',
            'proformaInvoice' => 'required|array',
            'proformaInvoice.*.product_id' => 'required|integer',
            'proformaInvoice.*.quantity' => 'required|numeric|min:1',
            'proformaInvoice.*.unit_price' => 'required|numeric',
            'proformaInvoice.*.discount' => 'nullable|numeric',
        ];
    }

    public function passedValidation()
    {
        $this->merge(SetDataOwnerService::forNonTransaction());
    }
}
