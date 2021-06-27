<?php

namespace App\Http\Requests;

use App\Traits\PrependCompanyId;
use App\Services\SetDataOwnerService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    use PrependCompanyId;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|unique:reservations,code,' . $this->route('reservation')->id,
            'reservation' => 'required|array',
            'reservation.*.product_id' => 'required|integer',
            'reservation.*.warehouse_id' => 'required|integer',
            'reservation.*.unit_price' => 'nullable|numeric',
            'reservation.*.quantity' => 'required|numeric|min:1',
            'reservation.*.description' => 'nullable|string',
            'customer_id' => 'nullable|integer',
            'sale_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'expires_on' => 'required|date',
            'payment_type' => 'required|string',
            'description' => 'nullable|string',
            'cash_received_in_percentage' => 'required|numeric|between:0,100',
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
        $this->merge(SetDataOwnerService::forUpdate());
    }
}
