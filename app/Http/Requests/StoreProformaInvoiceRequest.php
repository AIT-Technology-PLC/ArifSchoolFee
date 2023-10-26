<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\BatchSelectionIsRequiredOrProhibited;
use App\Rules\CanEditReferenceNumber;
use App\Rules\CheckBatchQuantity;
use App\Rules\CheckProductStatus;
use App\Rules\CheckValidBatchNumber;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateCustomFields;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProformaInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'prefix' => ['nullable', 'string'],
            'code' => ['required', 'string', new UniqueReferenceNum('proforma_invoices'), new CanEditReferenceNumber('proforma_invoices')],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'contact_id' => ['nullable', 'integer', new MustBelongToCompany('contacts')],
            'issued_on' => ['required', 'date', 'before_or_equal:now'],
            'expires_on' => ['nullable', 'date', 'after_or_equal:issued_on'],
            'terms' => ['nullable', 'string'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'proformaInvoice' => ['required', 'array'],
            'proformaInvoice.*.product_id' => ['required', 'string', Rule::in(Product::activeForSale()->pluck('id')), new CheckProductStatus],
            'proformaInvoice.*.quantity' => ['required', 'numeric', 'gt:0', new CheckBatchQuantity($this->input('proformaInvoice'))],
            'proformaInvoice.*.unit_price' => ['required', 'numeric'],
            'proformaInvoice.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'proformaInvoice.*.specification' => ['nullable', 'string'],
            'proformaInvoice.*.merchandise_batch_id' => [
                new BatchSelectionIsRequiredOrProhibited,
                Rule::forEach(fn($v, $a) => is_null($v) ? [] : ['integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber]),
            ],
            'customField.*' => [new ValidateCustomFields('proformainvoice')],
        ];
    }
}
