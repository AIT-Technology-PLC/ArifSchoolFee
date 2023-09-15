<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillOfMaterial extends Model
{
    use MultiTenancy, HasUserstamps, Approvable, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function billOfMaterialDetails()
    {
        return $this->hasMany(BillOfMaterialDetail::class);
    }

    public function jobDetails()
    {
        return $this->hasMany(JobDetail::class);
    }

    public function details()
    {
        return $this->billOfMaterialDetails;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function isUsedForProduction()
    {
        return $this
            ->jobDetails()
            ->where(function ($query) {
                $query->where('wip', '>', 0)
                    ->orWhere('available', '>', 0);
            })
            ->exists();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getUnitCost()
    {
        return $this
            ->join('bill_of_material_details', 'bill_of_materials.id', '=', 'bill_of_material_details.bill_of_material_id')
            ->join('products', 'bill_of_material_details.product_id', '=', 'products.id')
            ->selectRaw('
                SUM(
                    CASE
                        WHEN products.inventory_valuation_method = "fifo" THEN fifo_unit_cost
                        WHEN products.inventory_valuation_method = "lifo" THEN lifo_unit_cost
                        WHEN products.inventory_valuation_method = "average" THEN average_unit_cost
                    ELSE 0
                    END * bill_of_material_details.quantity
                ) as unit_cost
            ')
            ->first()
            ->unit_cost;
    }
}
