<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InventoryInTransitReportRequest extends FormRequest
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
        return [
            'transaction_type' => ['required', 'string', Rule::in(['transfers', 'purchases'])],
        ];
    }

    public function prepareForValidation()
    {
        $transactionType = null;

        if (!empty($this->input('transaction_type'))) {
            return;
        }

        if (isFeatureEnabled('Transfer Management')) {
            $transactionType = 'transfers';
        }

        if (!isFeatureEnabled('Transfer Management') && isFeatureEnabled('Purchase Management')) {
            $transactionType = 'purchases';
        }

        $this->merge([
            'transaction_type' => $transactionType,
        ]);
    }
}
