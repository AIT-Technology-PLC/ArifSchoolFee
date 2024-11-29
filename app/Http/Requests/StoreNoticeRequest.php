<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MustBelongToCompany;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class StoreNoticeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:30'],
            'notice_date' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'message' => ['required', 'string', 'max:150'],
            'warehouse_id' => ['required', 'array'],
            'warehouse_id.*' => ['required', 'integer', new MustBelongToCompany('warehouses')],
            'type' => ['required', 'array'],
            'type.*' => ['required', Rule::in(Role::pluck('name')->toArray())],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $recipient = $this->input('type');

            if (empty($recipient)) {
                $validator->errors()->add('base', 'Please choose recipients for the notice.');
            }
        });
    }
}
