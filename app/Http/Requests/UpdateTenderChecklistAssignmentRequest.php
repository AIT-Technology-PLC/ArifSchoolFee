<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTenderChecklistAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'checklist' => ['required', 'array'],
            'checklist.*.id' => ['required', 'integer'],
            'checklist.*.assigned_to' => ['nullable', 'integer', new MustBelongToCompany('employees', 'user_id')],
        ];
    }
}
