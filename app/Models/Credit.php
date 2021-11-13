<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'last_settled_at' => 'datetime',
        'issued_on' => 'datetime',
        'due_date' => 'datetime',
    ];

    protected $attributes = [
        'cash_amount' => 0,
        'credit_amount_settled' => 0,
    ];

    public function gdn()
    {
        return $this->belongsTo(Gdn::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creditSettlements()
    {
        return $this->hasMany(CreditSettlement::class);
    }

    public function getSettlementPercentageAttribute()
    {
        return ($this->credit_amount_settled / $this->credit_amount) * 100;
    }

    public function scopeSettled($query)
    {
        return $query->whereColumn('credit_amount', 'credit_amount_settled');
    }

    public function scopePartiallySettled($query)
    {
        return $query
            ->where('credit_amount_settled', '>', 0)
            ->whereColumn('credit_amount', '>', 'credit_amount_settled');
    }

    public function scopeNoSettlements($query)
    {
        return $query->where('credit_amount_settled', 0);
    }

    public function scopeAverageCreditSettlementDays($query, $customerId = null)
    {
        return $query
            ->when($customerId, fn($query) => $query->where('customer_id', $customerId))
            ->selectRaw('SUM(DATEDIFF(issued_on, last_settled_at)) / COUNT(id) as days')->first()->days;
    }

    public function isSettled()
    {
        return $this->credit_amount == $this->credit_amount_settled;
    }
}
