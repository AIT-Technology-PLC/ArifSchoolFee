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

    public function grnDetails()
    {
        return $this->hasMany(GrnDetail::class);
    }

    public function scopeCompanyWarehouses($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getAll()
    {
        return $this->companyWarehouses()->with(['createdBy', 'updatedBy'])->orderBy('name')->get();
    }

    public function getAllWithoutRelations()
    {
        return $this->companyWarehouses()->orderBy('name')->get();
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
