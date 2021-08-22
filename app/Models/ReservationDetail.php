<?php

namespace App\Models;

use App\Traits\Discountable;
use App\Traits\PricingProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationDetail extends Model
{
    use HasFactory, SoftDeletes, PricingProduct, Discountable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
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
        return $this
            ->where([
                ['warehouse_id', $warehouse->id],
                ['product_id', $product->id],
            ])
            ->whereIn('reservation_id', function ($query) {
                $query->select('id')
                    ->from('reservations')
                    ->where('company_id', userCompany()->id)
                    ->whereNotNull('reserved_by')
                    ->whereNull('cancelled_by')
                    ->whereNotIn('id',
                        Reservation::whereHasMorph(
                            'reservable',
                            [Gdn::class],
                            function (Builder $query) {
                                $query->where('status', 'Subtracted From Inventory');
                            })
                            ->pluck('id')
                    );
            })
            ->get()
            ->load(['reservation.customer', 'product', 'warehouse']);
    }
}
