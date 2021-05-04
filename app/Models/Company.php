<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'enabled' => 'boolean',
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

    public function prices()
    {
        return $this->hasMany(Price::class);
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

    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    public function isCompanyStandardMember()
    {
        return $this->membership_plan == "Standard";
    }

    public function isCompanyPremiumMember()
    {
        return $this->membership_plan == "Premium";
    }

    public function isCompanyProfessionalMember()
    {
        return $this->membership_plan == "Professional";
    }

    public function isCompanyPremiumOrProfessionalMember()
    {
        return $this->membership_plan == "Premium" || $this->membership_plan == "Professional";
    }
}
