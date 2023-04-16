<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'job.*.product_id' => ['required', 'integer', 'distinct', Rule::in($this->route('job')->jobDetails()->pluck('product_id'))],
            'job.*.available' => ['required', 'numeric', 'gte:0'],
        ];
    }
}
