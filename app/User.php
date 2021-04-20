<?php

namespace App;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Gdn;
use App\Models\GeneralTenderChecklist;
use App\Models\Grn;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Models\Price;
use App\Models\Tender;
use App\Models\TenderStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function merchandisesCreated()
    {
        return $this->hasMany(Merchandise::class, 'created_by');
    }

    public function merchandisesUpdated()
    {
        return $this->hasMany(Merchandise::class, 'updated_by');
    }

    public function manufacturingsCreated()
    {
        return $this->hasMany(Manufacturing::class, 'created_by');
    }

    public function manufacturingsUpdated()
    {
        return $this->hasMany(Manufacturing::class, 'updated_by');
    }

    public function rawMaterialsCreated()
    {
        return $this->hasMany(RawMaterial::class, 'created_by');
    }

    public function rawMaterialsUpdated()
    {
        return $this->hasMany(RawMaterial::class, 'updated_by');
    }

    public function billOfMaterialsCreated()
    {
        return $this->hasMany(BillOfMaterial::class, 'created_by');
    }

    public function billOfMaterialsUpdated()
    {
        return $this->hasMany(BillOfMaterial::class, 'updated_by');
    }

    public function mroItemsCreated()
    {
        return $this->hasMany(MroItem::class, 'created_by');
    }

    public function mroItemsUpdated()
    {
        return $this->hasMany(MroItem::class, 'updated_by');
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
}
