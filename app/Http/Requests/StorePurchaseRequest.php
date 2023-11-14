<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\BatchSelectionIsRequiredOrProhibited;
use App\Rules\CanEditReferenceNumber;
use App\Rules\CheckProductStatus;
use App\Rules\CheckSupplierDebtLimit;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidateCustomFields;
use App\Rules\VerifyCashReceivedAmountIsValid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('purchases'), new CanEditReferenceNumber('purchases')],
            'type' => ['required', 'string', Rule::in(['Local Purchase', 'Import'])],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers'),
                new CheckSupplierDebtLimit(
                    $this->get('purchase'),
                    $this->get('payment_type'),
                    $this->get('cash_paid_type'),
                    $this->get('cash_paid')
                )],
            'contact_id' => ['nullable', 'integer', new MustBelongToCompany('contacts')],
            'purchased_on' => ['required', 'date'],
            'payment_type' => ['required', 'string', Rule::when($this->input('type') == 'Import', Rule::in(['LC', 'TT', 'CAD']), Rule::in(['Cash Payment', 'Credit Payment'])), function ($attribute, $value, $fail) {
                if ($value == 'Credit Payment' && is_null($this->get('supplier_id'))) {
                    $fail('Credit Payment without supplier is not allowed, please select a supplier.');
                }
            }],
            'cash_paid_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($this->get('payment_type') == 'Cash Payment' && $value != 'percent') {
                    $fail('When payment type is "Cash Payment", the type should be "Percent".');
                }
            },
            ],
            'cash_paid' => ['bail', 'required', 'numeric', 'gte:0',
                new VerifyCashReceivedAmountIsValid(
                    $this->get('payment_type'),
                    0,
                    $this->get('purchase'),
                    $this->get('cash_paid_type')
                ),
            ],
            'due_date' => ['nullable', 'date', 'after:purchased_on', 'required_if:payment_type,Credit Payment', 'prohibited_if:payment_type,Cash Payment'],
            'tax_id' => ['nullable', 'integer', new MustBelongToCompany('taxes'), 'required_if:type,Local Purchase', 'prohibited_if:type,Import'],
            'currency' => ['nullable', 'string', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'exchange_rate' => ['nullable', 'numeric', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'freight_cost' => ['nullable', 'numeric', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'freight_insurance_cost' => ['nullable', 'numeric', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'freight_unit' => ['nullable', 'string', 'max:255', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'freight_amount' => ['nullable', 'numeric', 'gte:0', 'size:' . collect($this->input('purchase'))->sum('amount'), 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'other_costs_before_tax' => ['nullable', 'numeric', 'gte:0', 'required_if:type,Import', 'exclude_if:type,Local Purchase'],
            'other_costs_after_tax' => ['nullable', 'numeric', 'gte:0', 'required_if:type,Import', 'exclude_if:type,Local Purchase'],
            'description' => ['nullable', 'string'],
            'purchase' => ['required', 'array'],
            'purchase.*.product_id' => ['required', 'integer', Rule::in(Product::inventoryType()->activeForPurchase()->pluck('id')), new CheckProductStatus('activeForPurchase')],
            'purchase.*.quantity' => ['required', 'numeric', 'gt:0'],
            'purchase.*.batch_no' => [new BatchSelectionIsRequiredOrProhibited(false), Rule::forEach(fn($v, $a) => is_null($v) ? [] : ['string'])],
            'purchase.*.expires_on' => ['nullable', 'date'],
            'purchase.*.unit_price' => ['required', 'numeric', 'min:0'],
            'purchase.*.amount' => ['nullable', 'numeric', 'gt:0', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.duty_rate' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.excise_tax' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.vat_rate' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.surtax' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.withholding_tax' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'customField.*' => [new ValidateCustomFields('purchase')],
        ];
    }
}
