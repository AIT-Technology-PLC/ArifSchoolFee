<?php

namespace App;

use App\Models as Models;
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
        'name', 'email', 'password', 'warehouse_id',
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
        return $this->hasOne(Models\Employee::class, 'user_id');
    }

    public function employeesCreated()
    {
        return $this->hasMany(Models\Employee::class, 'created_by');
    }

    public function employeesUpdated()
    {
        return $this->hasMany(Models\Employee::class, 'updated_by');
    }

    public function warehousesCreated()
    {
        return $this->hasMany(Models\Warehouse::class, 'created_by');
    }

    public function warehousesUpdated()
    {
        return $this->hasMany(Models\Warehouse::class, 'updated_by');
    }

    public function suppliersCreated()
    {
        return $this->hasMany(Models\Supplier::class, 'created_by');
    }

    public function suppliersUpdated()
    {
        return $this->hasMany(Models\Supplier::class, 'updated_by');
    }

    public function productCategoriesCreated()
    {
        return $this->hasMany(Models\ProductCategory::class, 'created_by');
    }

    public function productCategoriesUpdated()
    {
        return $this->hasMany(Models\ProductCategory::class, 'updated_by');
    }

    public function productsCreated()
    {
        return $this->hasMany(Models\Product::class, 'created_by');
    }

    public function productsUpdated()
    {
        return $this->hasMany(Models\Product::class, 'updated_by');
    }

    public function purchasesCreated()
    {
        return $this->hasMany(Models\Purchase::class, 'created_by');
    }

    public function purchasesUpdated()
    {
        return $this->hasMany(Models\Purchase::class, 'updated_by');
    }

    public function customersCreated()
    {
        return $this->hasMany(Models\Customer::class, 'created_by');
    }

    public function customersUpdated()
    {
        return $this->hasMany(Models\Customer::class, 'updated_by');
    }

    public function salesCreated()
    {
        return $this->hasMany(Models\Sale::class, 'created_by');
    }

    public function salesUpdated()
    {
        return $this->hasMany(Models\Sale::class, 'updated_by');
    }

    public function gdnsCreated()
    {
        return $this->hasMany(Models\Gdn::class, 'created_by');
    }

    public function gdnsUpdated()
    {
        return $this->hasMany(Models\Gdn::class, 'updated_by');
    }

    public function gdnsApproved()
    {
        return $this->hasMany(Models\Gdn::class, 'approved_by');
    }

    public function transfersCreated()
    {
        return $this->hasMany(Models\Transfer::class, 'created_by');
    }

    public function transfersUpdated()
    {
        return $this->hasMany(Models\Transfer::class, 'updated_by');
    }

    public function transferApproved()
    {
        return $this->hasMany(Models\Transfer::class, 'approved_by');
    }

    public function purchaseOrdersCreated()
    {
        return $this->hasMany(Models\PurchaseOrder::class, 'created_by');
    }

    public function purchaseOrdersUpdated()
    {
        return $this->hasMany(Models\PurchaseOrder::class, 'updated_by');
    }

    public function grnsCreated()
    {
        return $this->hasMany(Models\Grn::class, 'created_by');
    }

    public function grnsUpdated()
    {
        return $this->hasMany(Models\Grn::class, 'updated_by');
    }

    public function grnsApproved()
    {
        return $this->hasMany(Models\Grn::class, 'approved_by');
    }

    public function pricesCreated()
    {
        return $this->hasMany(Models\Price::class, 'created_by');
    }

    public function pricesUpdated()
    {
        return $this->hasMany(Models\Price::class, 'updated_by');
    }

    public function generalTenderChecklistsCreated()
    {
        return $this->hasMany(Models\GeneralTenderChecklist::class, 'created_by');
    }

    public function generalTenderChecklistsUpdated()
    {
        return $this->hasMany(Models\GeneralTenderChecklist::class, 'updated_by');
    }

    public function tendersCreated()
    {
        return $this->hasMany(Models\Tender::class, 'created_by');
    }

    public function tendersUpdated()
    {
        return $this->hasMany(Models\Tender::class, 'updated_by');
    }

    public function tenderStatusesCreated()
    {
        return $this->hasMany(Models\TenderStatus::class, 'created_by');
    }

    public function tenderStatusesUpdated()
    {
        return $this->hasMany(Models\TenderStatus::class, 'updated_by');
    }

    public function sivsCreated()
    {
        return $this->hasMany(Models\Siv::class, 'created_by');
    }

    public function sivsUpdated()
    {
        return $this->hasMany(Models\Siv::class, 'updated_by');
    }

    public function sivsApproved()
    {
        return $this->hasMany(Models\Siv::class, 'approved_by');
    }

    public function proformaInvoicesCreated()
    {
        return $this->hasMany(Models\ProformaInvoice::class, 'created_by');
    }

    public function proformaInvoicesUpdated()
    {
        return $this->hasMany(Models\ProformaInvoice::class, 'updated_by');
    }

    public function proformaInvoicesConverted()
    {
        return $this->hasMany(Models\ProformaInvoice::class, 'converted_by');
    }

    public function damagesCreated()
    {
        return $this->hasMany(Models\Damage::class, 'created_by');
    }

    public function damagesUpdated()
    {
        return $this->hasMany(Models\Damage::class, 'updated_by');
    }

    public function damagesApproved()
    {
        return $this->hasMany(Models\Damage::class, 'approved_by');
    }

    public function adjustmentsCreated()
    {
        return $this->hasMany(Models\Adjustment::class, 'created_by');
    }

    public function adjustmentsUpdated()
    {
        return $this->hasMany(Models\Adjustment::class, 'updated_by');
    }

    public function adjustmentsApproved()
    {
        return $this->hasMany(Models\Adjustment::class, 'approved_by');
    }

    public function adjustmentsAdjusted()
    {
        return $this->hasMany(Models\Adjustment::class, 'adjusted_by');
    }

    public function returnsCreated()
    {
        return $this->hasMany(Models\Returnn::class, 'created_by');
    }

    public function returnsUpdated()
    {
        return $this->hasMany(Models\Returnn::class, 'updated_by');
    }

    public function returnsApproved()
    {
        return $this->hasMany(Models\Returnn::class, 'approved_by');
    }

    public function returnsReturned()
    {
        return $this->hasMany(Models\Returnn::class, 'returned_by');
    }

    public function reservationsCreated()
    {
        return $this->hasMany(Models\Reservation::class, 'created_by');
    }

    public function reservationsUpdated()
    {
        return $this->hasMany(Models\Reservation::class, 'updated_by');
    }

    public function reservationsApproved()
    {
        return $this->hasMany(Models\Reservation::class, 'approved_by');
    }

    public function reservationsReserved()
    {
        return $this->hasMany(Models\Reservation::class, 'reserved_by');
    }

    public function reservationsCancelled()
    {
        return $this->hasMany(Models\Reservation::class, 'cancelled_by');
    }

    public function reservationsConverted()
    {
        return $this->hasMany(Models\Reservation::class, 'converted_by');
    }

    public function tenderChecklistTypesCreated()
    {
        return $this->hasMany(Models\TenderChecklistType::class, 'created_by');
    }

    public function tenderChecklistTypesUpdated()
    {
        return $this->hasMany(Models\TenderChecklistType::class, 'updated_by');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Models\Warehouse::class)->withPivot('type');
    }

    public function warehouse()
    {
        return $this->belongsTo(Models\Warehouse::class);
    }
}
