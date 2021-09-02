<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferDetail extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        return $this->where('product_id', $product->id)
            ->where(function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse->id)
                    ->orWhere('to_warehouse_id', $warehouse->id);
            })
            ->whereIn('transfer_id', function ($query) {
                $query->select('id')
                    ->from('transfers')
                    ->where([
                        ['company_id', userCompany()->id],
                        ['status', 'Transferred'],
                    ]);
            })
            ->get()
            ->load(['transfer', 'product', 'toWarehouse', 'warehouse']);
    }
}
