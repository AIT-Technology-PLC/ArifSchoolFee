<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', new UniqueReferenceNum('reservations')],
            'reservation' => ['required', 'array'],
            'reservation.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'reservation.*.warehouse_id' => ['required', 'integer', Rule::in(auth()->user()->getAllowedWarehouses('sales')->pluck('id'))],
            'reservation.*.unit_price' => ['nullable', 'numeric'],
            'reservation.*.quantity' => ['required', 'numeric', 'min:1'],
            'reservation.*.description' => ['nullable', 'string'],
            'reservation.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'issued_on' => ['required', 'date'],
            'expires_on' => ['required', 'date', 'after_or_equal:issued_on'],
            'payment_type' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'cash_received_in_percentage' => ['required', 'numeric', 'between:0,100'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
