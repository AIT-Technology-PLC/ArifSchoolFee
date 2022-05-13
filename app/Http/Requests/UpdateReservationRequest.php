<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidatePrice;
use App\Rules\VerifyCashReceivedAmountIsValid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('reservations', $this->route('reservation')->id)],
            'reservation' => ['required', 'array'],
            'reservation.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'reservation.*.warehouse_id' => ['required', 'integer', Rule::in(auth()->user()->getAllowedWarehouses('sales')->pluck('id'))],
            'reservation.*.unit_price' => ['nullable', 'numeric', new ValidatePrice],
            'reservation.*.quantity' => ['required', 'numeric', 'gt:0'],
            'reservation.*.description' => ['nullable', 'string'],
            'reservation.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'issued_on' => ['required', 'date'],
            'expires_on' => ['required', 'date', 'after_or_equal:issued_on'],
            'payment_type' => ['required', 'string'],

            'payment_type' => ['required', 'string'],

            'cash_received_type' => ['required_if:payment_type,Credit Payment', 'string', new VerifyCashReceivedAmountIsValid($this->get('discount'), $this->get('reservation'), $this->get('cash_received_type')), function ($attribute, $value, $fail) {
                if ($this->get('cash_received') == 100 && $value == 'percent' && $this->get('payment_type') == 'Credit Payment') {
                    $fail('If "Cash Received" is 100%, then "Payment Type" should be "Cash Payment"');

                }
            }],

            'description' => ['nullable', 'string'],
            'cash_received' => ['required_if:payment_type,Credit Payment', 'nullable', 'numeric', 'gt:0'],
            'due_date' => ['nullable', 'date', 'after:issued_on', 'required_if:payment_type,Credit Payment'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
