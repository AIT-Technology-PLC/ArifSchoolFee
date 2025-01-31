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
            'position' => ['required', 'string'],
            'gender' => ['required', 'string', Rule::in(['male', 'female'])],
            'phone' => ['required', 'numeric', Rule::unique('employees')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('user')->id)->withoutTrashed()],
            'address' => ['required', 'string', 'max:50'],
            'warehouse_id' => ['sometimes', 'required', 'integer', Rule::when(isFeatureEnabled('Employee Transfer'), 'prohibited'), new MustBelongToCompany('warehouses')],
            'role' => ['sometimes', 'required', 'string', Rule::notIn(['System Manager']), Rule::when(authUser()->id == $this->route('user')->user->id || $this->route('user')->user->hasRole('System Manager'), 'prohibited')],
            'enabled' => ['sometimes', 'required', 'integer', 'max:1', Rule::when(authUser()->id == $this->route('user')->user->id || $this->route('user')->user->hasRole('System Manager'), 'prohibited')],
            'transactions' => ['nullable', 'array', Rule::when($this->route('user')->user->hasRole('System Manager'), 'prohibited')],
            'transactions.*' => ['nullable', 'integer', new MustBelongToCompany('warehouses')],
        ];
    }
}
