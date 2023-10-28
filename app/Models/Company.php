<?php

namespace App\Models;

use App\Utilities\WithholdingTax;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $appends = [
        'withholdingTaxes',
    ];

    protected $casts = [
        'enabled' => 'integer',
        'is_discount_before_vat' => 'integer',
        'is_price_before_vat' => 'integer',
        'is_convert_to_siv_as_approved' => 'integer',
        'can_show_branch_detail_on_print' => 'integer',
        'is_editing_reference_number_enabled' => 'integer',
        'is_backorder_enabled' => 'integer',
        'can_check_inventory_on_forms' => 'integer',
        'can_show_employee_job_title_on_print' => 'integer',
        'can_select_batch_number_on_forms' => 'integer',
        'filter_customer_and_supplier' => 'integer',
        'is_costing_by_freight_volume' => 'integer',
        'is_payroll_basic_salary_after_absence_deduction' => 'integer',
        'does_payroll_basic_salary_include_overtime' => 'integer',
        'is_return_limited_by_sales' => 'integer',
        'can_sale_subtract' => 'integer',
        'can_siv_subtract_from_inventory' => 'integer',
        'show_product_code_on_printouts' => 'integer',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function merchandises()
    {
        return $this->hasMany(Merchandise::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function gdns()
    {
        return $this->hasMany(Gdn::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function grns()
    {
        return $this->hasMany(Grn::class);
    }

    public function generalTenderChecklists()
    {
        return $this->hasMany(GeneralTenderChecklist::class);
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }

    public function tenderStatuses()
    {
        return $this->hasMany(TenderStatus::class);
    }

    public function sivs()
    {
        return $this->hasMany(Siv::class);
    }

    public function proformaInvoices()
    {
        return $this->hasMany(ProformaInvoice::class);
    }

    public function damages()
    {
        return $this->hasMany(Damage::class);
    }

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    public function limits()
    {
        return $this->morphToMany(Limit::class, 'limitable')->withPivot('amount');
    }

    public function features()
    {
        return $this->morphToMany(Feature::class, 'featurable')->withPivot('is_enabled');
    }

    public function returns()
    {
        return $this->hasMany(Returnn::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function tenderChecklists()
    {
        return $this->hasMany(TenderChecklistType::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function tenderOpportunities()
    {
        return $this->hasMany(TenderOpportunity::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function integrations()
    {
        return $this->belongsToMany(Integration::class);
    }

    public function billOfMaterials()
    {
        return $this->hasMany(BillOfMaterial::class);
    }

    public function compensations()
    {
        return $this->hasMany(Compensation::class);
    }

    public function leaveCategories()
    {
        return $this->hasMany(LeaveCategory::class);
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }

    public function customFields()
    {
        return $this->hasMany(CustomField::class);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    public function email(): Attribute
    {
        return Attribute::make(
            get: fn($value) => str()->lower($value) ?? ''
        );
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function getPriceMethod()
    {
        return $this->is_price_before_vat ? 'Before Tax' : 'After Tax';
    }

    public function getDiscountMethod()
    {
        return $this->is_discount_before_vat ? 'Before Tax' : 'After Tax';
    }

    public function isDiscountBeforeTax()
    {
        return $this->is_discount_before_vat;
    }

    public function isPriceBeforeTax()
    {
        return $this->is_price_before_vat;
    }

    public function isConvertToSivAsApproved()
    {
        return $this->is_convert_to_siv_as_approved;
    }

    public function canShowBranchDetailOnPrint()
    {
        return $this->can_show_branch_detail_on_print;
    }

    public function canShowEmployeeJobTitleOnPrint()
    {
        return $this->can_show_employee_job_title_on_print;
    }

    public function hasIntegration($integrationName)
    {
        return $this->integrations()
            ->where('name', $integrationName)
            ->where('integrations.is_enabled', 1)
            ->wherePivot('is_enabled', 1)
            ->exists();
    }

    public function hasPrintTemplate()
    {
        return $this->print_template_image;
    }

    public function isEditingReferenceNumberEnabled()
    {
        return $this->is_editing_reference_number_enabled;
    }

    public function isBackorderEnabled()
    {
        return $this->is_backorder_enabled;
    }

    public function isInventoryCheckerEnabled()
    {
        return $this->can_check_inventory_on_forms;
    }

    public function canSelectBatchNumberOnForms()
    {
        return $this->can_select_batch_number_on_forms;
    }

    public function filterAllCustomerAndSupplier()
    {
        return $this->filter_customer_and_supplier;
    }

    public function isCostingByFreightVolume()
    {
        return $this->is_costing_by_freight_volume;
    }

    public function isBasicSalaryAfterAbsenceDeduction()
    {
        return $this->is_payroll_basic_salary_after_absence_deduction;
    }

    public function doesBasicSalaryIncludeOvertime()
    {
        return $this->does_payroll_basic_salary_include_overtime;
    }

    public function getWithholdingTaxesAttribute()
    {
        return WithholdingTax::{str()->lower($this->income_tax_region)}();
    }

    public function isReturnLimitedBySales()
    {
        return $this->is_return_limited_by_sales;
    }

    public function canSaleSubtract()
    {
        return $this->can_sale_subtract;
    }

    public function creditIssuedOnDate($transaction)
    {
        if ($this->auto_generated_credit_issued_on_date == 'approval_date') {
            return now();
        }

        return $transaction->issued_on;
    }

    public function canSivSubtract()
    {
        return $this->can_siv_subtract_from_inventory;
    }

    public function showProductCodeOnPrintouts()
    {
        return $this->show_product_code_on_printouts;
    }
}
