<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'position' => 'required|string',
            'enabled' => 'sometimes|required|integer|max:1',
            'role' => 'sometimes|required|string',
        ];
    }

    public function passedValidation()
    {
        if (!$this->has('enabled')) {
            $this->enabled = 1;
        }

        $this->merge(SetDataOwnerService::forUpdate());
    }
}
