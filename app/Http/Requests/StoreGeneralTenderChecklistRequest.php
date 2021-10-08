<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class StoreGeneralTenderChecklistRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'item' => ['required', 'string'],
            'tender_checklist_type_id' => ['required', 'integer', new MustBelongToCompany('tender_checklist_types')],
            'description' => ['nullable', 'string'],
        ];
    }
}
