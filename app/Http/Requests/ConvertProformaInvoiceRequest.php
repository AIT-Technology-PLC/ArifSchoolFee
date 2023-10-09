<?php

namespace App\Http\Requests;

use App\Models\MerchandiseBatch;
use App\Rules\CheckCustomerCreditLimit;
use App\Rules\CheckCustomerDepositBalance;
use App\Rules\MustBelongToCompany;
use App\Rules\VerifyCashReceivedAmountIsValid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConvertProformaInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $details = $this->route('proforma_invoice')->proformaInvoiceDetails;

        return [
            'warehouse_id' => ['required', 'integer', Rule::in(authUser()->getAllowedWarehouses('sales')->pluck('id'))],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers'),
                new CheckCustomerCreditLimit(
                    $this->get('discount'),
                    $details,
                    $this->get('payment_type'),
                    $this->get('cash_received_type'),
                    $this->get('cash_received')
                ),
                new CheckCustomerDepositBalance(
                    $this->get('discount'),
                    $details,
                    $this->get('payment_type'),
                ),
            ],

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

            'cash_received' => ['bail', 'required', 'numeric', 'gte:0',
                new VerifyCashReceivedAmountIsValid(
                    $this->get('payment_type'),
                    $this->get('discount'),
                    $details,
                    $this->get('cash_received_type')
                ),
            ],

            'due_date' => ['nullable', 'date', 'after:issued_on', 'prohibited_unless:payment_type,Credit Payment'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'bank_name' => ['nullable', 'string', 'prohibited_if:payment_type,Cash Payment,Credit Payment'],
            'reference_number' => ['nullable', 'string', 'prohibited_if:payment_type,Cash Payment,Credit Payment'],
            'merchandiseBatches.*' => ['nullable', 'integer', new MustBelongToCompany('merchandise_batches'), function ($a, $v, $f) {
                $exists = MerchandiseBatch::whereRelation('merchandise', 'warehouse_id', $this->input('warehouse_id'))->where('id', $v)->exists();

                if (!empty($v) && !$exists) {
                    $f('Batch No:' . $v . ' does not exists in the selected branch.');
                }
            }],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'customer_id' => $this->route('proforma_invoice')->customer_id,
            'merchandiseBatches' => $this->route('proforma_invoice')->proformaInvoiceDetails()->pluck('merchandise_batch_id')->toArray(),
        ]);
    }
}
