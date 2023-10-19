<?php

namespace App\Http\Requests;

use App\Models\Debt;
use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierDebtSettlementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0', function ($a, $value, $fail) {
                $debtAmount = Debt::query()
                    ->where('supplier_id', $this->route('supplier')->id)
                    ->whereDate('issued_on', '<=', $this->input('settled_at'))
                    ->unsettled()
                    ->sum('debt_amount');

                $settledDebtAmount = Debt::query()
                    ->where('supplier_id', $this->route('supplier')->id)
                    ->whereDate('issued_on', '<=', $this->input('settled_at'))
                    ->unsettled()
                    ->sum('debt_amount_settled');

                $unsettledDebtAmount = $debtAmount - $settledDebtAmount;

                if ($value > $unsettledDebtAmount) {
                    $fail('The settlement amount has exceeded the debt amount.');
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
