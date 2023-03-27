<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProformaInvoiceExpiresOn extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'expires_on' => ['required', 'date', 'after_or_equal:now'],
        ];
    }
}