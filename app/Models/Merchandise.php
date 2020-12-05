<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'expires_on' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function scopeCompanyMerchandises($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function getAllOnHandMerchandises()
    {
        return $this->companyMerchandises()
            ->with(['product', 'warehouse'])
            ->where('total_on_hand', '>', 0)
            ->get();
    }

    public function getAllLimitedMerchandises($onHandMerchandises)
    {
        $limitedMerchandises = $onHandMerchandises->filter(function ($onHandMerchandise) {
            return $onHandMerchandise->total_on_hand <= $onHandMerchandise->product->min_on_hand;
        });

        return $limitedMerchandises;
    }

    public function isReturnedQuantityValueValid($returnedQuantity)
    {
        $totalSoldQuantity = $this->total_sold;
        $previousReturnedQuantity = $this->total_returns;

        if ($returnedQuantity < 0 || !is_numeric($returnedQuantity)) {
            return $previousReturnedQuantity;
        }

        if ($returnedQuantity <= $totalSoldQuantity) {
            return $returnedQuantity;
        }

        return $previousReturnedQuantity;
    }

    public function isBrokenQuantityValueValid($brokenQuantity)
    {
        $totalOnHandQuantity = $this->total_on_hand;
        $previousBrokenQuantity = $this->total_broken;

        if ($brokenQuantity < 0 || !is_numeric($brokenQuantity)) {
            return $previousBrokenQuantity;
        }

        if ($brokenQuantity <= $totalOnHandQuantity) {
            return $brokenQuantity;
        }

        return $previousBrokenQuantity;
    }

    public function decrementTotalOnHandQuantity()
    {
        $totalReceivedQuantity = $this->total_received;
        $totalSoldQuantity = $this->total_sold;
        $totalBrokenQuantity = $this->total_broken;

        $totalOnHand = $totalReceivedQuantity - ($totalSoldQuantity + $totalBrokenQuantity);

        $this->total_on_hand = $totalOnHand;

        $this->save();
    }
}
