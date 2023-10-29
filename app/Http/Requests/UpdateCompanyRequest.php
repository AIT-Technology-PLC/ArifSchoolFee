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
            'tin' => ['nullable', 'numeric', 'digits:10', Rule::unique('companies')->where('id', '<>', $this->route('company')->id)->withoutTrashed()],
            'proforma_invoice_prefix' => ['nullable', 'string', 'max:255'],
            'is_price_before_vat' => ['required', 'boolean'],
            'is_convert_to_siv_as_approved' => ['sometimes', 'required', 'boolean'],
            'is_editing_reference_number_enabled' => ['required', 'boolean'],
            'can_show_branch_detail_on_print' => ['required', 'boolean'],
            'income_tax_region' => ['sometimes', 'required', 'string', Rule::in(['Ethiopia'])],
            'payroll_bank_name' => ['nullable', 'string'],
            'payroll_bank_account_number' => ['nullable', 'string', 'required_unless:payroll_bank_name,null'],

            'is_discount_before_vat' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($this->get('is_price_before_vat') == 0 && $value == 1) {
                        $fail('If Unit Price Method is "After Tax", then Discount Method should be "After Grand Total Price"');
                    }
                },
            ],

            'logo' => ['sometimes', 'file', 'mimes:jpg,jpeg,png', 'max:5000'],
            'print_template_image' => ['sometimes', 'file', 'mimes:jpg,jpeg,png', 'max:5000'],
            'print_padding_top' => ['required', 'numeric', 'between:0,100'],
            'print_padding_bottom' => ['required', 'numeric', 'between:0,100'],
            'print_padding_horizontal' => ['required', 'numeric', 'between:0,100'],
            'paid_time_off_amount' => ['sometimes', 'required', 'numeric'],
            'paid_time_off_type' => ['sometimes', 'required', 'string', Rule::in(['Days', 'Hours'])],
            'working_days' => ['nullable', 'numeric', 'min:1', 'max:31'],
            'sales_report_source' => ['sometimes', 'required', 'string', 'max:255', Rule::in(['Delivery Orders', 'Invoices'])],
            'is_backorder_enabled' => ['sometimes', 'required', 'boolean'],
            'can_check_inventory_on_forms' => ['sometimes', 'required', 'boolean'],
            'can_show_employee_job_title_on_print' => ['required', 'boolean'],
            'can_select_batch_number_on_forms' => ['sometimes', 'required', 'boolean'],
            'expiry_in_days' => ['nullable', 'numeric', 'gt:0'],
            'filter_customer_and_supplier' => ['required', 'boolean'],
            'is_costing_by_freight_volume' => ['sometimes', 'required', 'boolean'],
            'is_payroll_basic_salary_after_absence_deduction' => ['sometimes', 'required', 'boolean'],
            'does_payroll_basic_salary_include_overtime' => ['sometimes', 'required', 'boolean'],
            'is_return_limited_by_sales' => ['sometimes', 'required', 'boolean'],
            'can_sale_subtract' => ['sometimes', 'required', 'boolean'],
            'auto_generated_credit_issued_on_date' => ['sometimes', 'required', Rule::in(['approval_date', 'issuance_date'])],
            'can_siv_subtract_from_inventory' => ['sometimes', 'required', 'boolean'],
            'is_partial_deliveries_enabled' => ['sometimes', 'required', 'boolean'],
            'show_product_code_on_printouts' => ['required', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'logo.max' => 'The Logo must be less than 5 megabytes',
            'print_template_image.max' => 'The Template must be less than 5 megabytes',
        ];
    }
}
