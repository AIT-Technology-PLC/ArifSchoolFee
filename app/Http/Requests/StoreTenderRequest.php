<?php

namespace App\Http\Requests;

use Illuminate\Support\Carbon;
use App\Services\SetDataOwnerService;
use Illuminate\Foundation\Http\FormRequest;

class StoreTenderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'participants' => 'nullable|integer|min:1',
            'bid_bond_amount' => 'nullable|string',
            'bid_bond_type' => 'nullable|string',
            'bid_bond_validity' => 'nullable|integer',
            'price' => 'nullable|string',
            'payment_term' => 'nullable|string',
            'published_on' => 'required|date',
            'closing_date' => 'required|date|after_or_equal:published_on',
            'opening_date' => 'required|date|after:closing_date',
            'customer_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'tender' => 'required|array',
            'tender.*.product_id' => 'required|integer',
            'tender.*.quantity' => 'required|numeric',
            'tender.*.description' => 'nullable|string',
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'closing_date' => (new Carbon($this->closing_date))->toDateTimeString(),
            'opening_date' => (new Carbon($this->opening_date))->toDateTimeString()
        ]);

        $this->merge(SetDataOwnerService::forNonTransaction());
    }
}
