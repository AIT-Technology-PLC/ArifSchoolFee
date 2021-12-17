<?php

namespace App\Models;

use App\Models\Damage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'enabled' => 'boolean',
        'is_discount_before_vat' => 'boolean',
        'is_price_before_vat' => 'boolean',
        'is_convert_to_siv_as_approved' => 'boolean',
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

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
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
        return $this->hasMany(ProformaInvoice::clas);
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

    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function getPriceMethod()
    {
        return $this->is_price_before_vat ? 'Before VAT' : 'After VAT';
    }

    public function getDiscountMethod()
    {
        return $this->is_discount_before_vat ? 'Before VAT' : 'After VAT';
    }

    public function isDiscountBeforeVAT()
    {
        return $this->is_discount_before_vat;
    }

    public function isPriceBeforeVAT()
    {
        return $this->is_price_before_vat;
    }

    public function isConvertToSivAsApproved()
    {
        return $this->is_convert_to_siv_as_approved;
    }
}
