<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_number' => ['required', 'string', 'max:20', Rule::unique('vehicles')->where('company_id', userCompany()->id)->where('id', '<>', $this->route('vehicle')->id)->withoutTrashed()],
            'vehicle_model' => ['required', 'string', 'max:25'],
            'year_made' => ['nullable', 'integer','gte:0', 'lte:' . date('Y')],
            'driver_name' => ['required', 'string','max:30'],
            'driver_phone' => ['required', 'string','max:15', 'unique:vehicles'],
            'note' => ['nullable', 'string', 'max:100'],
        ];
    }
}
