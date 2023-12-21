<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMerchandiseBatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'batch_no' => ['required', 'string', Rule::unique('merchandise_batches')
                    ->withoutTrashed()
                    ->ignore($this->route('merchandise_batch')->id)
                    ->where('merchandise_id', $this->route('merchandise_batch')->merchandise_id)
                    ->where('expires_on', $this->input('expires_on')),
            ],
            'expires_on' => ['nullable', 'date'],
        ];
    }
}
