<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'currency' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'email', 'max:40', Rule::unique('companies')->where('id', '<>', $this->route('school')->id)->withoutTrashed()],
            'phone' => ['nullable', 'string', 'max:15', Rule::unique('companies')->where('id', '<>', $this->route('school')->id)->withoutTrashed()],
            'address' => ['nullable', 'string', 'max:50'],
            'tin' => ['nullable', 'numeric', 'digits:10', Rule::unique('companies')->where('id', '<>', $this->route('school')->id)->withoutTrashed()],
            'can_show_branch_detail_on_print' => ['required', 'boolean'],
            'logo' => ['sometimes', 'file', 'mimes:jpg,jpeg,png', 'max:1024'],
            'print_template_image' => ['sometimes', 'file', 'mimes:jpg,jpeg,png', 'max:1024'],
            'print_padding_top' => ['required', 'numeric', 'between:0,100'],
            'print_padding_bottom' => ['required', 'numeric', 'between:0,100'],
            'print_padding_horizontal' => ['required', 'numeric', 'between:0,100'],
            'paid_time_off_amount' => ['sometimes', 'required', 'numeric'],
            'is_in_training' => ['required', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'logo.max' => 'The Logo must be less than 1 megabytes',
            'print_template_image.max' => 'The Template must be less than 1 megabytes',
        ];
    }
}
