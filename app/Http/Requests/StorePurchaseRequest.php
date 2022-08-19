<?php

namespace App\Http\Requests;

use App\Rules\CheckSupplierDebtLimit;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
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
            'code' => ['required', 'string', new UniqueReferenceNum('purchases')],
            'type' => ['required', 'string', Rule::in(['Local Purchase', 'Import'])],
            'supplier_id' => ['nullable', 'integer', new MustBelongToCompany('suppliers'),
                new CheckSupplierDebtLimit(
                    $this->get('purchase'),
                    $this->get('payment_type'),
                    $this->get('cash_payed_type'),
                    $this->get('cash_payed')
                )],
            'purchased_on' => ['required', 'date'],
            'payment_type' => ['required', 'string', Rule::when($this->input('type') == 'Import', Rule::in(['LC', 'TT', 'CAD']), Rule::in(['Cash Payment', 'Debt Payment'])), function ($attribute, $value, $fail) {
                if ($value == 'Debt Payment' && is_null($this->get('supplier_id'))) {
                    $fail('Debt Payment without supplier is not allowed, please select a supplier.');
                }
            }],
            'cash_payed_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($this->get('payment_type') == 'Cash Payment' && $value != 'percent') {
                    $fail('When payment type is "Cash Payment", the type should be "Percent".');
                }
            },
            ],
            'cash_payed' => ['bail', 'required', 'numeric', 'gte:0',
                new VerifyCashReceivedAmountIsValid(
                    $this->get('payment_type'),
                    0,
                    $this->get('purchase'),
                    $this->get('cash_payed_type')
                ),
            ],
            'due_date' => ['nullable', 'date', 'after:issued_on', 'required_if:payment_type,Debt Payment', 'prohibited_if:payment_type,Cash Payment'],
            'tax_type' => ['nullable', 'string', Rule::in(['VAT', 'TOT', 'None']), 'required_if:type,Local Purchase', 'prohibited_if:type,Import'],
            'currency' => ['nullable', 'string', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'exchange_rate' => ['nullable', 'numeric', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'description' => ['nullable', 'string'],
            'purchase' => ['required', 'array'],
            'purchase.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'purchase.*.quantity' => ['required', 'numeric', 'gt:0'],
            'purchase.*.unit_price' => ['required', 'numeric'],
            'purchase.*.freight_cost' => ['nullable', 'numeric', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.freight_insurance_cost' => ['nullable', 'numeric', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.duty_rate' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.excise_tax' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.vat_rate' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.surtax' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
            'purchase.*.withholding_tax' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:type,Import', 'prohibited_if:type,Local Purchase'],
        ];
    }
}
