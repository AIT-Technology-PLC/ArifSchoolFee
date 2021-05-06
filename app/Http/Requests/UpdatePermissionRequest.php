<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'permissions' => 'nullable|array',
            'permissions.*' => 'nullable|string',
        ];
    }

    public function passedValidation()
    {
        $this->replace([
            'permissions' => $this->has('permissions') ? $this->only('permissions') : null,
        ]);
    }
}
