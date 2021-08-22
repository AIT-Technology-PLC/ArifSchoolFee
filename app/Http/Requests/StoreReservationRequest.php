<?php

namespace App\Http\Requests;

use App\Traits\PrependCompanyId;
use App\Services\SetDataOwnerService;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:reservations',
            'reservation' => 'required|array',
            'reservation.*.product_id' => 'required|integer',
            'reservation.*.warehouse_id' => 'required|integer',
            'reservation.*.unit_price' => 'nullable|numeric',
            'reservation.*.quantity' => 'required|numeric|min:1',
            'reservation.*.description' => 'nullable|string',
            'reservation.*.discount' => 'nullable|numeric|min:0|max:100',
            'customer_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'expires_on' => 'required|date|after_or_equal:issued_on',
            'payment_type' => 'required|string',
            'description' => 'nullable|string',
            'cash_received_in_percentage' => 'required|numeric|between:0,100',
            'discount' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'code' => $this->prependCompanyId($this->code),
        ]);
    }

    public function passedValidation()
    {
        $this->merge(SetDataOwnerService::forNonTransaction());
    }
}
