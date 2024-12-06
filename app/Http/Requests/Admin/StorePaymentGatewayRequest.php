<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueMerchantID;

class StorePaymentGatewayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer', new MustBelongToCompany('companies')],
            'payment_method_id' => ['required', 'integer', new MustBelongToCompany('payment_methods')],
            'merchant_id' => ['required','string','max:20', function ($attribute, $value, $fail) {
                $rule = new UniqueMerchantID($this->input("company_id"),$this->input("payment_method_id"), $value);

                if (!$rule->passes($attribute, $value)) { $fail($rule->message());}}
            ],
        ];
    }
}
