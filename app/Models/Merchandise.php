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

    public function getAllLimitedMerchandises()
    {
        $limitedMerchandises = $this->getAllOnHandMerchandises()->filter(function ($merchandiseOnHand) {
            return $merchandiseOnHand->total_on_hand <= $merchandiseOnHand->product->min_on_hand;
        });

        return $limitedMerchandises;
    }
}
