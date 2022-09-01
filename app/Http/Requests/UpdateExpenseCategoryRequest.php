<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenseCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'distinct', Rule::unique('expense_categories')->where(function ($query) {
                return $query->where('warehouse_id', authUser()->warehouse_id)->where('id', '<>', $this->route('expense_category')->id);
            })],
        ];
    }
}