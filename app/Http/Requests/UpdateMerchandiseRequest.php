<?php

namespace App\Http\Requests;

use App\Services\SetDataOwnerService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMerchandiseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'total_returns' => 'nullable|numeric',
            'total_broken' => 'nullable|numeric',
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'total_returns' => $this->route('merchandise')->isAddedQuantityValueValid($this->total_returns),
            'total_broken' => $this->route('merchandise')->isBrokenQuantityValueValid($this->total_broken),
        ]);

        $this->merge(SetDataOwnerService::forUpdate());
    }
}
