<?php

namespace App\Http\Requests;

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
            'tender_status_id' => ['required', 'integer', new MustBelongToCompany('tender_statues')],
            'code' => ['required', 'string', 'max:255'],
            'source' => ['required', 'string', 'max:255'],
            'published_on' => ['required', 'date'],
            'body' => ['required', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric'],
            'comments' => ['nullable', 'string'],
        ];
    }
}
