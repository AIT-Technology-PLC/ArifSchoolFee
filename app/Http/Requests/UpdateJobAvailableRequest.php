<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\ValidateChessisTracker;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJobAvailableRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'job' => ['required', 'array'],
            'job.*.product_id' => ['required', 'integer', 'distinct', new MustBelongToCompany('products')],
            'job.*.available' => ['required', 'numeric', 'gte:0'],
            'job.*.chassisTracker.*.chassis_number' => ['required', 'string', 'unique:chassis_numbers',
                new ValidateChessisTracker($this->get('job'))],
            'job.*.chassisTracker.*.engine_number' => ['required', 'string', 'unique:chassis_numbers'],
        ];
    }
}
