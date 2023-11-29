<?php

namespace App\Http\Requests;

use App\Rules\BatchSelectionIsRequiredOrProhibited;
use App\Rules\CheckValidBatchNumber;
use App\Rules\ValidateCustomFields;
use App\Rules\ValidateDeliveredQuantity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConvertGdnToSivRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $gdnDetails = $this->route('gdn')->gdnDetails;

        return [
            'gdn' => ['sometimes', 'required', 'array'],
            'gdn.*.product_id' => ['required_with:gdn', 'integer', Rule::in($gdnDetails->pluck('product_id'))],
            'gdn.*.warehouse_id' => ['required_with:gdn', 'integer', Rule::in($gdnDetails->pluck('warehouse_id'))],
            'gdn.*.quantity' => ['required_with:gdn', 'numeric', 'min:0', new ValidateDeliveredQuantity($this->route('gdn'))],
            'gdn.*.merchandise_batch_id' => ['nullable',
                new BatchSelectionIsRequiredOrProhibited,
                Rule::forEach(fn($v, $a) => is_null($v) ? [] : ['integer', Rule::in($gdnDetails->pluck('merchandise_batch_id')), new CheckValidBatchNumber]),
            ],
            'master.received_by' => ['nullable', 'string'],
            'master.delivered_by' => ['nullable', 'string'],
            'customField.*' => [new ValidateCustomFields('siv')],
        ];
    }
}
