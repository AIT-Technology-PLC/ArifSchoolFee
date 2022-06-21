<?php

namespace App\Http\Requests;

use App\Rules\CheckCustomerCreditLimit;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidatePrice;
use App\Rules\VerifyCashReceivedAmountIsValid;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('sales')],
            'sale' => ['required', 'array'],
            'sale.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'sale.*.unit_price' => ['nullable', 'numeric', new ValidatePrice],
            'sale.*.quantity' => ['required', 'numeric', 'gt:0'],
            'sale.*.description' => ['nullable', 'string'],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers'), new CheckCustomerCreditLimit($this->get('discount'),
                $this->get('sale'),
                $this->get('payment_type'),
                $this->get('cash_received_type'),
                $this->get('cash_received'))],
            'issued_on' => ['required', 'date'],
            'payment_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($value == 'Credit Payment' && is_null($this->get('customer_id'))) {
                    $fail('Creating a credit for delivery order that has no customer is not allowed.');
                }
            }],

            'cash_received_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($this->get('payment_type') == 'Cash Payment' && $value != 'percent') {
                    $fail('When payment type is "Cash Payment", the type should be "Percent".');
                }
            }],

            'description' => ['nullable', 'string'],

            'cash_received' => ['required', 'numeric', 'gte:0', new VerifyCashReceivedAmountIsValid($this->get('discount'), $this->get('sale'), $this->get('cash_received_type')), function ($attribute, $value, $fail) {
                if ($this->get('cash_received_type') == 'percent' && $value > 100) {
                    $fail('When type is "Percent", the percentage amount must be between 0 and 100.');
                }
                if ($this->get('payment_type') == 'Cash Payment' && $value != 100) {
                    $fail('When payment type is "Cash Payment", the percentage amount must be 100.');
                }
            }],
        ];
    }
}
