<?php

namespace App\Http\Requests;

use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'integer', new UniqueReferenceNum('announcements')],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'warehouse_id' => ['required', 'array'],
            'warehouse_id.*' => ['required', 'integer', new MustBelongToCompany('warehouses')],
        ];
    }
}
