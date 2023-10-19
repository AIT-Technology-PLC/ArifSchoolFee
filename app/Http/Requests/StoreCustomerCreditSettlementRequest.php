<?php

namespace App\Http\Requests;

use App\Models\Credit;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerCreditSettlementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0', function ($a, $value, $fail) {
                $creditAmount = Credit::query()
                    ->where('customer_id', $this->route('customer')->id)
                    ->whereDate('issued_on', '<=', $this->input('settled_at'))
                    ->unsettled()
                    ->sum('credit_amount');

                $settledCreditAmount = Credit::query()
                    ->where('customer_id', $this->route('customer')->id)
                    ->whereDate('issued_on', '<=', $this->input('settled_at'))
                    ->unsettled()
                    ->sum('credit_amount_settled');

                $unsettledCreditAmount = $creditAmount - $settledCreditAmount;

                if ($value > $unsettledCreditAmount) {
                    $fail('The settlement amount has exceeded the credit amount.');
                }
            }],
            'method' => ['required', 'string'],
            'bank_name' => ['nullable', 'string', 'required_unless:method,Cash', 'exclude_if:method,Cash'],
            'reference_number' => ['nullable', 'string', 'required_unless:method,Cash', 'exclude_if:method,Cash'],
            'settled_at' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
