<?php

namespace App\Models;

use App\Scopes\ActiveWarehouseScope;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_sales_store' => 'boolean',
        'can_be_sold_from' => 'boolean',
    ];

    public static function booted()
    {
        static::addGlobalScope(new ActiveWarehouseScope);
    }

    public function merchandises()
    {
        return $this->hasMany(Merchandise::class);
    }

    public function gdnDetails()
    {
        return $this->hasMany(GdnDetail::class);
    }

    public function grnDetails()
    {
        return $this->hasMany(GrnDetail::class);
    }

    public function sivDetails()
    {
        return $this->hasMany(SivDetail::class);
    }

    public function damageDetails()
    {
        return $this->hasMany(DamageDetail::class);
    }

    public function adjustmentDetails()
    {
        return $this->hasMany(AdjustmentDetail::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class);
    }

    public function reservationDetails()
    {
        return $this->hasMany(ReservationDetail::class);
    }

    public function originalUsers()
    {
        return $this->hasMany(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('type');
    }

    public function fromTransfers()
    {
        return $this->hasMany(Warehouse::class, 'transferred_from');
    }

    public function toTransfers()
    {
        return $this->hasMany(Warehouse::class, 'transferred_to');
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function getWarehousesInUseQuery()
    {
        return $this
            ->whereIn('id', auth()->user()->getAllowedWarehouses('read')->pluck('id'))
            ->whereHas('merchandises', function ($query) {
                $query->where(function ($query) {
                    $query->where('available', '>', 0)
                        ->orWhere('reserved', '>', 0);
                });
            });
    }
}
