<?php

namespace App\Models;

use App\Scopes\TransferScope;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parentModel()
    {
        return $this->transfer;
    }

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        return $this->where('product_id', $product->id)
            ->whereIn('transfer_id', function ($query) use ($warehouse) {
                $query->select('id')
                    ->from('transfers')
                    ->where('company_id', userCompany()->id)
                    ->whereNotNull('subtracted_by')
                    ->where(function ($query) use ($warehouse) {
                        $query->where('transferred_from', $warehouse->id)
                            ->orWhere('transferred_to', $warehouse->id);
                    });
            })
            ->get()
            ->load([
                'transfer' => function ($query) {
                    return $query->withoutGlobalScopes([TransferScope::class])
                        ->with(['transferredTo', 'transferredFrom']);
                }]
            );
    }
}
