<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Debit extends Model
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
        'debit_amount_settled' => 0,
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function debitSettlements()
    {
        return $this->hasMany(DebitSettlement::class);
    }

    public function getSettlementPercentageAttribute()
    {
        return ($this->debit_amount_settled / $this->debit_amount) * 100;
    }

    public function getDebitAmountUnsettledAttribute()
    {
        return $this->debit_amount - $this->debit_amount_settled;
    }

    public function scopeSettled($query)
    {
        return $query->whereColumn('debit_amount', 'debit_amount_settled');
    }

    public function scopePartiallySettled($query)
    {
        return $query
            ->where('debit_amount_settled', '>', 0)
            ->whereColumn('debit_amount', '>', 'debit_amount_settled');
    }

    public function scopeNoSettlements($query)
    {
        return $query->where('debit_amount_settled', 0);
    }

    public function scopeUnsettled($query)
    {
        return $query->whereColumn('debit_amount', '>', 'debit_amount_settled');
    }

    public function scopeAverageDebitSettlementDays($query)
    {
        return ($query->selectRaw('SUM(DATEDIFF(last_settled_at, issued_on)) / COUNT(id) as days')
                ->first()
                ->days) ?? 0.00;
        }

        public function isSettled()
    {
        return $this->debit_amount == $this->debit_amount_settled;
    }
}
