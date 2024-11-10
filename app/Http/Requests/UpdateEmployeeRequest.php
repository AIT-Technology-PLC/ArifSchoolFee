<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:30', Rule::unique('users')->ignore($this->route('user')->user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'gender' => ['required', 'string', 'max:5', Rule::in(['male', 'female'])],
            'phone' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:50'],
            'warehouse_id' => ['sometimes', 'required', 'integer', Rule::when(isFeatureEnabled('Employee Transfer'), 'prohibited'), new MustBelongToCompany('warehouses')],
            'role' => ['sometimes', 'required', 'string', Rule::notIn(['System Manager']), Rule::when(authUser()->id == $this->route('user')->user->id || $this->route('user')->user->hasRole('System Manager'), 'prohibited')],
            'enabled' => ['sometimes', 'required', 'integer', 'max:1', Rule::when(authUser()->id == $this->route('user')->user->id || $this->route('user')->user->hasRole('System Manager'), 'prohibited')],
            'transactions' => ['nullable', 'array', Rule::when($this->route('user')->user->hasRole('System Manager'), 'prohibited')],
            'transactions.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
        ];
    }
}
