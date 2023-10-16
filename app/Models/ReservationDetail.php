<?php

namespace App\Models;

use App\Scopes\ActiveWarehouseScope;
use App\Scopes\BranchScope;
use App\Traits\PricingProduct;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationDetail extends Model
{
    use HasFactory, SoftDeletes, PricingProduct, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $appends = [
        'originalUnitPrice',
    ];

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
        return $this->belongsTo(Warehouse::class)->withoutGlobalScopes([ActiveWarehouseScope::class]);
    }

    public function merchandiseBatch()
    {
        return $this->belongsTo(MerchandiseBatch::class);
    }

    public function parentModel()
    {
        return $this->reservation;
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
                        Reservation::withoutGlobalScopes([BranchScope::class])
                            ->whereHasMorph(
                                'reservable',
                                [Gdn::class, Sale::class],
                                function (Builder $query) {
                                    $query->withoutGlobalScopes([BranchScope::class])
                                        ->whereNotNull('subtracted_by');
                                })
                            ->pluck('id')
                    );
            })
            ->get()
            ->load([
                'reservation' => function ($query) {
                    return $query->withoutGlobalScopes([BranchScope::class])->with(['customer']);
                }]
            );
    }
}
