<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
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
            'code' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'bid_bond_amount' => ['nullable', 'string'],
            'bid_bond_type' => ['nullable', 'string'],
            'bid_bond_validity' => ['nullable', 'integer'],
            'price' => ['nullable', 'string'],
            'payment_term' => ['nullable', 'string'],
            'published_on' => ['required', 'date'],
            'closing_date' => ['required', 'date', 'after_or_equal:published_on'],
            'opening_date' => ['required', 'date', 'after:closing_date'],
            'clarify_on' => ['nullable', 'date', 'after:published_on'],
            'visit_on' => ['nullable', 'date', 'after:published_on'],
            'premeet_on' => ['nullable', 'date', 'after:published_on'],
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'description' => ['nullable', 'string'],
            'lot' => ['required', 'array'],
            'lot.*.lotDetails.*.product_id' => ['required', 'integer', new MustBelongToCompany('products')],
            'lot.*.lotDetails.*.quantity' => ['required', 'numeric'],
            'lot.*.lotDetails.*.description' => ['nullable', 'string'],
        ];
    }
}
