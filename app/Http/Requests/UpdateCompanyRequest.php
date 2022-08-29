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
            'sector' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'tin' => ['nullable', 'numeric', 'digits:10', Rule::unique('companies')->where(function ($query) {
                return $query->where('id', '<>', $this->route('company')->id);
            })],
            'proforma_invoice_prefix' => ['nullable', 'string', 'max:255'],
            'is_price_before_vat' => ['required', 'boolean'],
            'is_convert_to_siv_as_approved' => ['required', 'boolean'],
            'is_editing_reference_number_enabled' => ['required', 'boolean'],
            'can_show_branch_detail_on_print' => ['required', 'boolean'],
            'income_tax_region' => ['required', 'string', Rule::in(['Ethiopia'])],

            'is_discount_before_vat' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($this->get('is_price_before_vat') == 0 && $value == 1) {
                        $fail('If Unit Price Method is "After VAT", then Discount Method should be "After Grand Total Price"');
                    }
                },
            ],

            'logo' => ['sometimes', 'file', 'mimes:jpg,jpeg,png'],
            'print_template_image' => ['sometimes', 'file', 'mimes:jpg,jpeg,png'],
            'print_padding_top' => ['required', 'numeric', 'between:0,100'],
            'print_padding_bottom' => ['required', 'numeric', 'between:0,100'],
            'print_padding_horizontal' => ['required', 'numeric', 'between:0,100'],
            'paid_time_off_amount' => ['nullable', 'numeric'],
            'paid_time_off_type' => ['nullable', 'string', Rule::in(['Days', 'Hours'])],
            'working_days' => ['nullable', 'numeric', 'min:1', 'max:30'],
            'sales_report_source' => ['required', 'string', 'max:255', Rule::in(['All Delivery Orders', 'Approved Delivery Orders', 'Subtracted Delivery Orders', 'All Invoices', 'Approved Invoices'])],
        ];
    }
}
