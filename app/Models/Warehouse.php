<?php

namespace App\Models;

use App\Models\ExchangeDetail;
use App\Scopes\ActiveWarehouseScope;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_sales_store' => 'boolean',
        'can_be_sold_from' => 'boolean',
    ];

    protected $cascadeDeletes = [
        'merchandises',
        'gdnDetails',
        'grnDetails',
        'sivDetails',
        'damageDetails',
        'adjustmentDetails',
        'returnDetails',
        'reservationDetails',
        'originalUsers',
        'jobs',
        'transfers',
        'fromTransfers',
        'toTransfers',
        'productReorders',
        'exchangeDetails',
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

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function fromTransfers()
    {
        return $this->hasMany(Transfer::class, 'transferred_from');
    }

    public function toTransfers()
    {
        return $this->hasMany(Transfer::class, 'transferred_to');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'factory_id');
    }

    public function productReorders()
    {
        return $this->hasMany(ProductReorder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->withoutGlobalScopes([ActiveWarehouseScope::class])->where('is_active', 0);
    }

    public function getWarehousesInUseQuery()
    {
        return $this
            ->whereIn('id', authUser()->getAllowedWarehouses('read')->pluck('id'))
            ->whereHas('merchandises', function ($query) {
                $query->where(function ($query) {
                    $query->where('available', '>', 0)
                        ->orWhere('reserved', '>', 0);
                });
            });
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function isSalesStore()
    {
        return $this->is_sales_store;
    }

    public function isCanBeSoldFrom()
    {
        return $this->can_be_sold_from;
    }

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class);
    }

    public function inventoryHistories()
    {
        return $this->hasMany(InventoryHistory::class);
    }

    public function hasPosIntegration()
    {
        return $this->company->hasIntegration('Point of Sale') && !is_null($this->pos_provider) && !is_null($this->host_address);
    }

    public function exchangeDetails()
    {
        return $this->hasMany(ExchangeDetail::class);
    }
}
