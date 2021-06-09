<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DamageDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function damage()
    {
        return $this->belongsTo(Damage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        return $this->where([
            ['warehouse_id', $warehouse->id],
            ['product_id', $product->id],
        ])
            ->whereIn('damage_id', function ($query) {
                $query->select('id')
                    ->from('damages')
                    ->where([
                        ['company_id', userCompany()->id],
                        ['status', 'Subtracted From Inventory'],
                    ]);
            })
            ->get()
            ->load(['damage', 'warehouse', 'product']);
    }
}
