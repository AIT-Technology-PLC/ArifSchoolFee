<?php

namespace App\Http\Requests;

use App\Models\Sale;
use App\Models\Product;
use App\Rules\ValidatePrice;
use Illuminate\Validation\Rule;
use App\Rules\CheckBatchQuantity;
use App\Rules\CheckProductStatus;
use App\Rules\UniqueReferenceNum;
use App\Rules\MustBelongToCompany;
use App\Rules\CheckValidBatchNumber;
use App\Rules\CheckCustomerCreditLimit;
use App\Rules\CheckCustomerDepositBalance;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\VerifyCashReceivedAmountIsValid;
use App\Rules\BatchSelectionIsRequiredOrProhibited;

class UpdateSaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('sales', $this->route('sale')->id), Rule::excludeIf(!userCompany()->isEditingReferenceNumberEnabled())],
            'fs_number' => ['sometimes', Rule::when(!is_null($this->route('sale')->fs_number), 'prohibited', 'nullable'), 'numeric', Rule::notIn(Sale::pluck('fs_number'))],
            'sale' => ['required', 'array'],
            'sale.*.product_id' => ['required', 'integer', Rule::in(Product::activeForSale()->pluck('id')), new CheckProductStatus],
            'sale.*.unit_price' => ['required', 'numeric', 'min:0', new ValidatePrice],
            'sale.*.quantity' => ['required', 'numeric', 'gt:0', new CheckBatchQuantity($this->input('sale'))],
            'sale.*.description' => ['nullable', 'string'],
            'sale.*.merchandise_batch_id' => [
                new BatchSelectionIsRequiredOrProhibited, 
                Rule::forEach(fn($v,$a) => is_null($v) ? [] : ['integer', new MustBelongToCompany('merchandise_batches'), new CheckValidBatchNumber]),
            ],

            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers'),
                Rule::when(
                    !$this->route('sale')->isApproved() && !$this->route('sale')->isCancelled(),
                    new CheckCustomerCreditLimit(
                        $this->get('discount'),
                        $this->get('sale'),
                        $this->get('payment_type'),
                        $this->get('cash_received_type'),
                        $this->get('cash_received')
                    )
                ),
                Rule::when(
                    !$this->route('sale')->isApproved() && !$this->route('sale')->isCancelled(),
                    new CheckCustomerDepositBalance(
                        $this->get('discount'),
                        $this->get('sale'),
                        $this->get('payment_type'),
                    )
                ),
            ],

            'contact_id' => ['nullable', 'integer', new MustBelongToCompany('contacts')],
            'issued_on' => ['required', 'date'],
            'payment_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($value == 'Credit Payment' && is_null($this->get('customer_id'))) {
                    $fail('Credit Payment without customer is not allowed, please select a customer.');
                }

                if ($value == 'Deposits' && is_null($this->get('customer_id'))) {
                    $fail('Deposits Payment without customer is not allowed, please select a customer.');
                }
            },
            ],

            'cash_received_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($this->get('payment_type') != 'Credit Payment' && $value != 'percent') {
                    $fail('When payment type is not "Credit Payment", the type should be "Percent".');
                }
            },
            ],

            'description' => ['nullable', 'string'],

            'cash_received' => ['bail', 'required', 'numeric', 'gte:0',
                new VerifyCashReceivedAmountIsValid(
                    $this->get('payment_type'),
                    $this->get('discount'),
                    $this->get('sale'),
                    $this->get('cash_received_type')
                ),
            ],

            'due_date' => ['nullable', 'date', 'after:issued_on', 'required_if:payment_type,Credit Payment', 'prohibited_if:payment_type,Cash Payment'],
            'bank_name' => ['nullable', 'string', 'prohibited_if:payment_type,Cash Payment,Credit Payment'],
            'reference_number' => ['nullable', 'string', 'prohibited_if:payment_type,Cash Payment,Credit Payment'],
            'has_withholding' => ['nullable', 'boolean']
        ];
    }
}
