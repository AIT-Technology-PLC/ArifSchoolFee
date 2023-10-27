<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\HasCustomFields;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Debt extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, HasFactory, HasCustomFields;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'last_settled_at' => 'datetime',
        'issued_on' => 'datetime',
        'due_date' => 'datetime',
    ];

    protected $attributes = [
        'cash_amount' => 0,
        'debt_amount_settled' => 0,
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function debtSettlements()
    {
        return $this->hasMany(DebtSettlement::class);
    }

    public function getSettlementPercentageAttribute()
    {
        return ($this->debt_amount_settled / $this->debt_amount) * 100;
    }

    public function getDebtAmountUnsettledAttribute()
    {
        return $this->debt_amount - $this->debt_amount_settled;
    }

    public function scopeSettled($query)
    {
        return $query->whereColumn('debt_amount', 'debt_amount_settled');
    }

    public function scopePartiallySettled($query)
    {
        return $query
            ->where('debt_amount_settled', '>', 0)
            ->whereColumn('debt_amount', '>', 'debt_amount_settled');
    }

    public function scopeNoSettlements($query)
    {
        return $query->where('debt_amount_settled', 0);
    }

    public function scopeUnsettled($query)
    {
        return $query->whereColumn('debt_amount', '>', 'debt_amount_settled');
    }

    public function scopeAverageDebtSettlementDays($query)
    {
        return ($query->selectRaw('SUM(DATEDIFF(last_settled_at, issued_on)) / COUNT(id) as days')
                ->first()
                ->days) ?? 0.00;
        }

        public function isSettled()
    {
        return $this->debt_amount == $this->debt_amount_settled;
    }
}
