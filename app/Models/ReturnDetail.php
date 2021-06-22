<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function returnn()
    {
        return $this->belongsTo(Returnn::class, 'return_id');
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
            ->whereIn('return_id', function ($query) {
                $query->select('id')
                    ->from('returns')
                    ->where('company_id', userCompany()->id)
                    ->whereNotNull('returned_by');
            })
            ->get()
            ->load(['return', 'product', 'warehouse']);
    }
}
