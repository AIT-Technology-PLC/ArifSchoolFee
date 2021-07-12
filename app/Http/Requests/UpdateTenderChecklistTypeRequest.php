<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTenderChecklistTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
        ];
    }

    public function passedValidation()
    {
        $this->merge(SetDataOwnerService::forUpdate());
    }
}
