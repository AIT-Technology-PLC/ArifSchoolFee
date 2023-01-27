<?php

namespace App\Http\Requests;

use App\Models\MerchandiseBatch;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProformaInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'prefix' => ['nullable', 'string'],
            'code' => ['required', 'string', new UniqueReferenceNum('proforma_invoices', $this->route('proforma_invoice')->id),
                Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'contact_id' => ['nullable', 'integer', new MustBelongToCompany('contacts')],
            'issued_on' => ['required', 'date'],
            'expires_on' => ['nullable', 'date', 'after_or_equal:issued_on'],
            'terms' => ['nullable', 'string'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'proformaInvoice' => ['required', 'array'],
            'proformaInvoice.*.product_id' => ['required', 'string', new MustBelongToCompany('products')],
            'proformaInvoice.*.quantity' => ['required', 'numeric', 'gt:0', function ($attribute, $value, $fail) {
                if (MerchandiseBatch::where('id', $this->input(str_replace('.quantity', '.merchandise_batch_id', $attribute)))->where('quantity', '<', $value)->exists()) {
                    $fail('There is no sufficient amount in this batch, please check your inventory.');
                }
            }],
            'proformaInvoice.*.unit_price' => ['required', 'numeric'],
            'proformaInvoice.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'proformaInvoice.*.specification' => ['nullable', 'string'],
            'proformaInvoice.*.merchandise_batch_id' => [' nullable', 'integer', new MustBelongToCompany('merchandise_batches'), function ($attribute, $value, $fail) {
                $merchandiseBatch = MerchandiseBatch::firstwhere('id', $value);
                if ($merchandiseBatch->merchandise->product_id != $this->input(str_replace('.merchandise_batch_id', '.product_id', $attribute))) {
                    $fail('Invalid Batch Number!');
                }
            }],
        ];
    }
}
