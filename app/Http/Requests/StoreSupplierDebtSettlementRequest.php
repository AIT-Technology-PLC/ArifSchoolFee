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
                $debtAmount = Debt::where('supplier_id', $this->route('supplier')->id)->unsettled()->sum('debt_amount');
                $settledDebtAmount = Debt::where('supplier_id', $this->route('supplier')->id)->unsettled()->sum('debt_amount_settled');
                $unsettledDebtAmount = $debtAmount - $settledDebtAmount;

                if ($value > $unsettledDebtAmount) {
                    $fail('The settlement amount has exceeded the debt amount.');
                }
            }],
            'method' => ['required', 'string'],
            'bank_name' => ['nullable', 'string', 'required_unless:method,Cash', 'exclude_if:method,Cash'],
            'reference_number' => ['nullable', 'string', 'required_unless:method,Cash', 'exclude_if:method,Cash'],
            'settled_at' => ['required', 'date', function ($a, $value, $fail) {
                $issuedOnDate = Debt::query()
                    ->where('supplier_id', $this->route('supplier')->id)
                    ->unsettled()
                    ->whereDate('issued_on', '>', $value)
                    ->max('issued_on');

                if (!empty($issuedOnDate)) {
                    $fail('The settlement date should be after ' . carbon($issuedOnDate)->toDateString());
                }

            }],
            'description' => ['nullable', 'string'],
        ];
    }
}
