<?php

namespace App;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Gdn;
use App\Models\GeneralTenderChecklist;
use App\Models\Grn;
use App\Models\Models\Siv;
use App\Models\Price;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Tender;
use App\Models\TenderStatus;
use App\Models\Transfer;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_online_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function employeesCreated()
    {
        return $this->hasMany(Employee::class, 'created_by');
    }

    public function employeesUpdated()
    {
        return $this->hasMany(Employee::class, 'updated_by');
    }

    public function warehousesCreated()
    {
        return $this->hasMany(Warehouse::class, 'created_by');
    }

    public function warehousesUpdated()
    {
        return $this->hasMany(Warehouse::class, 'updated_by');
    }

    public function suppliersCreated()
    {
        return $this->hasMany(Supplier::class, 'created_by');
    }

    public function suppliersUpdated()
    {
        return $this->hasMany(Supplier::class, 'updated_by');
    }

    public function productCategoriesCreated()
    {
        return $this->hasMany(ProductCategory::class, 'created_by');
    }

    public function productCategoriesUpdated()
    {
        return $this->hasMany(ProductCategory::class, 'updated_by');
    }

    public function productsCreated()
    {
        return $this->hasMany(Product::class, 'created_by');
    }

    public function productsUpdated()
    {
        return $this->hasMany(Product::class, 'updated_by');
    }

    public function purchasesCreated()
    {
        return $this->hasMany(Purchase::class, 'created_by');
    }

    public function purchasesUpdated()
    {
        return $this->hasMany(Purchase::class, 'updated_by');
    }

    public function customersCreated()
    {
        return $this->hasMany(Customer::class, 'created_by');
    }

    public function customersUpdated()
    {
        return $this->hasMany(Customer::class, 'updated_by');
    }

    public function salesCreated()
    {
        return $this->hasMany(Sale::class, 'created_by');
    }

    public function salesUpdated()
    {
        return $this->hasMany(Sale::class, 'updated_by');
    }

    public function gdnsCreated()
    {
        return $this->hasMany(Gdn::class, 'created_by');
    }

    public function gdnsUpdated()
    {
        return $this->hasMany(Gdn::class, 'updated_by');
    }

    public function gdnsApproved()
    {
        return $this->hasMany(Gdn::class, 'approved_by');
    }

    public function transfersCreated()
    {
        return $this->hasMany(Transfer::class, 'created_by');
    }

    public function transfersUpdated()
    {
        return $this->hasMany(Transfer::class, 'updated_by');
    }

    public function transferApproved()
    {
        return $this->hasMany(Transfer::class, 'approved_by');
    }

    public function purchaseOrdersCreated()
    {
        return $this->hasMany(PurchaseOrder::class, 'created_by');
    }

    public function purchaseOrdersUpdated()
    {
        return $this->hasMany(PurchaseOrder::class, 'updated_by');
    }

    public function grnsCreated()
    {
        return $this->hasMany(Grn::class, 'created_by');
    }

    public function grnsUpdated()
    {
        return $this->hasMany(Grn::class, 'updated_by');
    }

    public function grnsApproved()
    {
        return $this->hasMany(Grn::class, 'approved_by');
    }

    public function pricesCreated()
    {
        return $this->hasMany(Price::class, 'created_by');
    }

    public function pricesUpdated()
    {
        return $this->hasMany(Price::class, 'updated_by');
    }

    public function generalTenderChecklistsCreated()
    {
        return $this->hasMany(GeneralTenderChecklist::class, 'created_by');
    }

    public function generalTenderChecklistsUpdated()
    {
        return $this->hasMany(GeneralTenderChecklist::class, 'updated_by');
    }

    public function tendersCreated()
    {
        return $this->hasMany(Tender::class, 'created_by');
    }

    public function tendersUpdated()
    {
        return $this->hasMany(Tender::class, 'updated_by');
    }

    public function tenderStatusesCreated()
    {
        return $this->hasMany(TenderStatus::class, 'created_by');
    }

    public function tenderStatusesUpdated()
    {
        return $this->hasMany(TenderStatus::class, 'updated_by');
    }

    public function sivsCreated()
    {
        return $this->hasMany(Siv::class, 'created_by');
    }

    public function sivsUpdated()
    {
        return $this->hasMany(Siv::class, 'updated_by');
    }

    public function sivsApproved()
    {
        return $this->hasMany(Siv::class, 'approved_by');
    }

    public function sivsExecuted()
    {
        return $this->hasMany(Siv::class, 'executed_by');
    }
}
