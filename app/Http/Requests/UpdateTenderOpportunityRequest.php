<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTenderOpportunityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers')],
            'tender_status_id' => ['required', 'integer', new MustBelongToCompany('tender_statuses')],
            'code' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string', 'max:255'],
            'published_on' => ['required', 'date'],
            'body' => ['required', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'max:255', 'required_unless:price,null'],
            'price' => ['nullable', 'numeric', 'gt:0', 'required_unless:currency,null'],
            'comments' => ['nullable', 'string'],
        ];
    }
}
