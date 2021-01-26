<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function merchandises()
    {
        return $this->hasMany(Merchandise::class);
    }

    public function manufacturings()
    {
        return $this->hasMany(Manufacturing::class);
    }

    public function rawMaterials()
    {
        return $this->hasMany(RawMaterial::class);
    }

    public function mroItems()
    {
        return $this->hasMany(MroItem::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function gdnDetails()
    {
        return $this->hasMany(GdnDetail::class);
    }

    public function transferDetails()
    {
        return $this->hasMany(TransferDetail::class, 'warehouse_id');
    }

    public function toTransferDetails()
    {
        return $this->hasMany(TransferDetail::class, 'to_warehouse_id');
    }

    public function scopeCompanyWarehouses($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function getAll()
    {
        return $this->companyWarehouses()->with(['createdBy', 'updatedBy'])->get();
    }

    public function getAllWithoutRelations()
    {
        return $this->companyWarehouses()->get();
    }

    public function countWarehousesOfCompany()
    {
        return $this->companyWarehouses()->count();
    }

    public function getTotalWarehousesUsed($onHandMerchandises)
    {
        $totalWarehousesUsed = $onHandMerchandises->groupBy('warehouse_id')->count();

        return $totalWarehousesUsed;
    }
}
