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

            'cash_received_type' => ['required', 'string', function ($attribute, $value, $fail) {
                if ($this->get('payment_type') == 'Cash Payment' && $value != 'percent') {
                    $fail('When payment type is "Cash Payment", the type should be "Percent".');
                }
            }],

            'cash_received' => ['required', 'numeric', 'gt:0', new VerifyCashReceivedAmountIsValid($this->get('discount'), $this->get('reservation'), $this->get('cash_received_type')), function ($attribute, $value, $fail) {
                if ($this->get('cash_received_type') == 'percent' && $value > 100) {
                    $fail('When type is "Percent", the percentage amount must be between 0 and 100.');
                }
                if ($this->get('payment_type') == 'Cash Payment' && $value != 100) {
                    $fail('When payment type is "Cash Payment", the percentage amount must be 100.');
                }
            }],

            'description' => ['nullable', 'string'],
            'cash_received' => ['required_if:payment_type,Credit Payment', 'nullable', 'numeric', 'gt:0'],
            'due_date' => ['nullable', 'date', 'after:issued_on', 'required_if:payment_type,Credit Payment', 'prohibited_if:payment_type,Cash Payment'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100', 'prohibited_if:payment_type,Cash Payment'],
        ];
    }
}
